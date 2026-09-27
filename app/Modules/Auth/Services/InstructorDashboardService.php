<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseAnalyticsService;
use App\Modules\CourseDiscussion\Models\CourseDiscussion;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Order\Models\OrderItem;
use Illuminate\Support\Collection;

class InstructorDashboardService
{
    public function __construct(
        protected CourseAnalyticsService $analyticsService = new CourseAnalyticsService()
    ) {}

    /**
     * Get aggregate analytic metrics for the instructor studio.
     */
    public function getMetrics(User $user): array
    {
        $courseIds = $user->authoredCourses()->pluck('id');

        $totalCourses = $courseIds->count();
        $publishedCourses = $user->authoredCourses()->where('status', 'APPROVED')->count();

        // 1. Students count (distinct enrolled students)
        $studentsCount = Enrollment::query()
            ->whereIn('course_id', $courseIds)
            ->where('status', 'ACTIVE')
            ->distinct('user_id')
            ->count('user_id');

        // 2. Course completion rate
        $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();
        $completedEnrollments = Enrollment::whereIn('course_id', $courseIds)
            ->where(function ($q) {
                $q->where('status', 'COMPLETED')
                    ->orWhere('progress_percentage', '>=', 100.00);
            })
            ->count();

        $completionRate = $totalEnrollments > 0
            ? round(($completedEnrollments / $totalEnrollments) * 100, 1)
            : 0.0;

        // 3. Average rating across approved reviews
        $averageRating = (float) round(
            CourseReview::whereIn('course_id', $courseIds)
                ->where('status', 'approved')
                ->avg('rating') ?? 0.0,
            1
        );

        $reviewsCount = CourseReview::whereIn('course_id', $courseIds)
            ->where('status', 'approved')
            ->count();

        // 4. Revenue summary
        $totalRevenue = (float) OrderItem::whereIn('course_id', $courseIds)
            ->whereHas('order.payments', function ($q) {
                $q->where('status', 'COMPLETED');
            })
            ->sum('total_price');

        // 5. Discussion & student questions count
        $totalQuestions = CourseDiscussion::whereIn('course_id', $courseIds)
            ->whereNull('parent_id')
            ->count();

        $unansweredQuestions = CourseDiscussion::whereIn('course_id', $courseIds)
            ->whereNull('parent_id')
            ->doesntHave('replies')
            ->count();

        // Pending assignment submissions to grade
        $pendingReviewsCount = AssignmentSubmission::query()
            ->whereHas('assignment', function ($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })
            ->where('status', 'SUBMITTED')
            ->count();

        return [
            'total_courses' => $totalCourses,
            'published_courses' => $publishedCourses,
            'students_count' => $studentsCount,
            'total_enrollments' => $totalEnrollments,
            'completion_rate' => $completionRate,
            'average_rating' => $averageRating,
            'reviews_count' => $reviewsCount,
            'revenue_summary' => $totalRevenue,
            'currency' => 'SAR',
            'questions_count' => $totalQuestions,
            'unanswered_questions' => $unansweredQuestions,
            'pending_assignment_reviews' => $pendingReviewsCount,
        ];
    }

    /**
     * Get instructor's courses list with enrollment stats.
     */
    public function getCourses(User $user, int $limit = 6): Collection
    {
        return $user->authoredCourses()
            ->withCount(['enrollments', 'sections', 'reviews'])
            ->with('category')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get pending assignment submissions waiting for instructor review.
     */
    public function getPendingAssignmentReviews(User $user, int $limit = 5): Collection
    {
        $courseIds = $user->authoredCourses()->pluck('id');

        return AssignmentSubmission::query()
            ->whereHas('assignment', function ($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })
            ->where('status', 'SUBMITTED')
            ->with(['assignment.course', 'user'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Build the entire instructor dashboard payload.
     */
    public function getDashboardData(User $user): array
    {
        $user->loadMissing('instructorProfile');

        return [
            'user' => $user,
            'profile' => $user->instructorProfile,
            'metrics' => $this->getMetrics($user),
            'courses' => $this->getCourses($user),
            'pending_reviews' => $this->getPendingAssignmentReviews($user),
        ];
    }
}
