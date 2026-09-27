<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\DeviceSession\Models\DeviceSession;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Payment\Models\Payment;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\User\Models\InstructorProfile;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    /**
     * Get platform enterprise high-level metrics.
     */
    public function getPlatformStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_students' => User::where('status', 'active')
                ->whereHas('roles', fn ($q) => $q->where('name', 'student'))
                ->count(),
            'instructors_count' => User::whereHas('roles', fn ($q) => $q->where('name', 'instructor'))->count(),
            'pending_instructor_profiles' => InstructorProfile::where('profile_status', 'pending')->count(),
            'total_courses' => Course::count(),
            'pending_course_approvals' => Course::where('status', 'SUBMITTED')->count(),
            'published_courses' => Course::where('status', 'APPROVED')->count(),
            'active_enrollments' => Enrollment::where('status', 'ACTIVE')->count(),
            'active_device_sessions' => DeviceSession::where('is_active', true)->count(),
            'total_revenue' => Payment::where('status', 'COMPLETED')->sum('amount'),
            'completed_transactions' => Payment::where('status', 'COMPLETED')->count(),
            'open_tickets' => SupportTicket::whereIn('status', ['OPEN', 'IN_PROGRESS'])->count(),
        ];
    }

    /**
     * Get courses pending administrative review and approval.
     */
    public function getPendingCourses(int $limit = 5): Collection
    {
        return Course::query()
            ->where('status', 'SUBMITTED')
            ->with(['instructor', 'category'])
            ->latest('updated_at')
            ->take($limit)
            ->get();
    }

    /**
     * Get recent platform users overview.
     */
    public function getRecentUsers(int $limit = 6): Collection
    {
        return User::query()
            ->with('roles')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get recent payments overview.
     */
    public function getRecentPayments(int $limit = 5): Collection
    {
        return Payment::query()
            ->with(['order.user'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Build the entire admin control center dashboard payload.
     */
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getPlatformStats(),
            'pending_courses' => $this->getPendingCourses(5),
            'recent_users' => $this->getRecentUsers(6),
            'recent_payments' => $this->getRecentPayments(5),
        ];
    }
}
