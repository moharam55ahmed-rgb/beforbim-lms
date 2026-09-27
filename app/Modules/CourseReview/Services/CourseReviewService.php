<?php

namespace App\Modules\CourseReview\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Models\Course;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Enrollment\Models\Enrollment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CourseReviewService
{
    /**
     * Submit or update a course review by an enrolled student.
     */
    public function submitReview(User $student, Course $course, int $rating, ?string $reviewText = null): CourseReview
    {
        $isEnrolled = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['ACTIVE', 'COMPLETED'])
            ->exists();

        if (! $isEnrolled) {
            throw ValidationException::withMessages([
                'course' => ['لا يمكن تقييم الدورة إلا للطلاب المسجلين فعلياً في هذا المسار الهندسي.'],
            ]);
        }

        $review = CourseReview::updateOrCreate(
            [
                'course_id' => $course->id,
                'student_id' => $student->id,
            ],
            [
                'rating' => max(1, min(5, $rating)),
                'review_text' => $reviewText,
                'status' => 'pending', // Requires admin moderation
            ]
        );

        AuditLog::log(
            module: 'Course',
            action: 'REVIEW_SUBMITTED',
            actor: $student,
            target: $review,
            newValues: ['rating' => $rating, 'review_id' => $review->id],
            reason: 'Student submitted course review and rating'
        );

        return $review;
    }

    /**
     * Moderate a student review (approve or reject).
     */
    public function moderateReview(CourseReview $review, string $status, ?string $adminFeedback, User $admin): CourseReview
    {
        $oldStatus = $review->status;
        $review->status = in_array($status, ['approved', 'rejected'], true) ? $status : 'rejected';
        $review->admin_feedback = $adminFeedback;
        $review->save();

        AuditLog::log(
            module: 'Course',
            action: 'REVIEW_MODERATED',
            actor: $admin,
            target: $review,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $review->status],
            reason: 'Admin moderation decision on course review'
        );

        return $review;
    }

    /**
     * Get paginated pending reviews for admin moderation.
     */
    public function getPendingReviews(int $perPage = 20): LengthAwarePaginator
    {
        return CourseReview::with(['course', 'student'])
            ->where('status', 'pending')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all reviews with optional status filter for admin.
     */
    public function getAllReviewsFiltered(?string $status = null, int $perPage = 20): LengthAwarePaginator
    {
        $query = CourseReview::with(['course', 'student'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }
}
