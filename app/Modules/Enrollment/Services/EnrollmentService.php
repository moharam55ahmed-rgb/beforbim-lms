<?php

namespace App\Modules\Enrollment\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Notification\Events\EnrollmentApproved;
use App\Modules\Order\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EnrollmentService
{
    /**
     * Activate enrollments for a verified, completed order.
     * 
     * STRICT RULES:
     * 1. Payment must be verified/completed before enrollment activation.
     * 2. Buying one course never unlocks another course: enrollments are created strictly per ordered item.
     * 3. Prevents duplicate active enrollments.
     */
    public function activateEnrollmentForOrder(Order $order): Collection
    {
        return DB::transaction(function () use ($order) {
            // Rule 1: Payment verification check
            $hasValidPayment = $order->payments()
                ->whereIn('status', ['COMPLETED', 'SUCCESS'])
                ->exists();

            if (! $hasValidPayment) {
                throw new RuntimeException("لا يمكن تفعيل الاشتراك: الطلب رقم {$order->order_number} لم يتم التحقق من سداده المالي بعد.");
            }

            $user = $order->user;
            $activatedEnrollments = collect();

            // Rule 2: Iterate through each specific purchased item
            foreach ($order->items as $item) {
                $courseId = $item->course_id;

                if (! $courseId) {
                    continue;
                }

                // Rule 3: Check for existing active enrollment
                $existing = Enrollment::where('user_id', $user->id)
                    ->where('course_id', $courseId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($existing) {
                    $activatedEnrollments->push($existing);
                    continue;
                }

                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'order_id' => $order->id,
                    'enrollable_type' => Course::class,
                    'enrollable_id' => $courseId,
                    'source' => 'DIRECT_PURCHASE',
                    'status' => 'ACTIVE',
                    'progress_percentage' => 0.00,
                    'enrolled_at' => now(),
                    'expires_at' => null, // Lifetime access by default
                ]);

                // Record status history
                $enrollment->recordStatusChange('ACTIVE', "Activation via paid Order #{$order->order_number}", $user);

                // Audit log
                AuditLog::log(
                    'Enrollment',
                    'ENROLLMENT_ACTIVATED',
                    $user,
                    $enrollment,
                    null,
                    ['course_id' => $courseId, 'order_id' => $order->id],
                    "Activation via paid Order #{$order->order_number}"
                );

                // Dispatch domain event
                event(new EnrollmentApproved($enrollment));

                $activatedEnrollments->push($enrollment);
            }

            return $activatedEnrollments;
        });
    }

    /**
     * Grant manual enrollment by an authorized administrator.
     */
    public function grantManualEnrollment(User $user, Course $course, User $admin, ?string $reason = null): Enrollment
    {
        return DB::transaction(function () use ($user, $course, $admin, $reason) {
            $existing = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', 'ACTIVE')
                ->first();

            if ($existing) {
                return $existing;
            }

            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrollable_type' => Course::class,
                'enrollable_id' => $course->id,
                'source' => 'ADMIN_GRANT',
                'status' => 'ACTIVE',
                'progress_percentage' => 0.00,
                'enrolled_at' => now(),
                'expires_at' => null,
            ]);

            // Record status history
            $enrollment->recordStatusChange('ACTIVE', $reason ?: 'Manual grant by administrator', $admin);

            AuditLog::log(
                'Enrollment',
                'MANUAL_ENROLLMENT_GRANTED',
                $admin,
                $enrollment,
                null,
                ['user_id' => $user->id, 'course_id' => $course->id],
                $reason ?: 'Manual grant by administrator'
            );

            event(new EnrollmentApproved($enrollment));

            return $enrollment;
        });
    }

    /**
     * Suspend an active enrollment.
     */
    public function suspendEnrollment(Enrollment $enrollment, User $admin, string $reason): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $admin, $reason) {
            $enrollment->update(['status' => 'SUSPENDED']);
            $enrollment->recordStatusChange('SUSPENDED', $reason, $admin);

            AuditLog::log(
                'Enrollment',
                'ENROLLMENT_SUSPENDED',
                $admin,
                $enrollment,
                null,
                ['enrollment_id' => $enrollment->id, 'user_id' => $enrollment->user_id, 'reason' => $reason],
                $reason
            );

            return $enrollment;
        });
    }

    /**
     * Revoke an enrollment (e.g. refund, violation).
     */
    public function revokeEnrollment(Enrollment $enrollment, User $admin, string $reason): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $admin, $reason) {
            $enrollment->update(['status' => 'REVOKED']);
            $enrollment->recordStatusChange('REVOKED', $reason, $admin);

            AuditLog::log(
                'Enrollment',
                'ENROLLMENT_REVOKED',
                $admin,
                $enrollment,
                null,
                ['enrollment_id' => $enrollment->id, 'user_id' => $enrollment->user_id, 'reason' => $reason],
                $reason
            );

            return $enrollment;
        });
    }

    /**
     * Revoke all active enrollments linked to a specific order.
     */
    public function revokeEnrollmentsForOrder(Order $order, User $admin, string $reason): int
    {
        $enrollments = Enrollment::where('order_id', $order->id)
            ->whereIn('status', ['ACTIVE', 'SUSPENDED'])
            ->get();

        foreach ($enrollments as $enrollment) {
            $this->revokeEnrollment($enrollment, $admin, $reason);
        }

        return $enrollments->count();
    }

    /**
     * Mark an enrollment as expired.
     */
    public function expireEnrollment(Enrollment $enrollment, ?string $reason = null): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $reason) {
            $enrollment->update(['status' => 'EXPIRED']);
            $enrollment->recordStatusChange('EXPIRED', $reason ?: 'Access period expired', null);

            AuditLog::log(
                'Enrollment',
                'ENROLLMENT_EXPIRED',
                $enrollment->user,
                $enrollment,
                null,
                ['enrollment_id' => $enrollment->id]
            );

            return $enrollment;
        });
    }

    /**
     * Check if a user has active non-expired access to a course.
     */
    public function hasActiveAccess(User $user, Course $course): bool
    {
        return $user->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'ACTIVE')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}
