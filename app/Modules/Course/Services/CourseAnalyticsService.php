<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Progress\Models\LessonProgress;

class CourseAnalyticsService
{
    /**
     * Get aggregate analytic metrics for a specific course.
     */
    public function getCourseMetrics(Course $course): array
    {
        $enrollments = $course->enrollments()->get();
        $totalEnrollments = $enrollments->count();
        $completedEnrollments = $enrollments->where('status', 'COMPLETED')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;
        $averageProgress = $totalEnrollments > 0 ? round($enrollments->avg('progress_percentage'), 1) : 0;

        $approvedReviews = $course->approvedReviews()->get();
        $reviewsCount = $approvedReviews->count();
        $averageRating = $reviewsCount > 0 ? round($approvedReviews->avg('rating'), 1) : 0;

        $lessons = $course->lessons;
        $lessonsCount = $lessons->count();
        $totalDurationMinutes = round($lessons->sum('duration_seconds') / 60);

        // Lesson engagement: total completed lesson marks across all students
        $lessonIds = $lessons->pluck('id');
        $totalLessonCompletions = LessonProgress::whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();

        // Progress distribution buckets for future charts
        $distribution = [
            '0_to_25' => $enrollments->whereBetween('progress_percentage', [0, 25])->count(),
            '26_to_50' => $enrollments->whereBetween('progress_percentage', [25.01, 50])->count(),
            '51_to_75' => $enrollments->whereBetween('progress_percentage', [50.01, 75])->count(),
            '76_to_100' => $enrollments->whereBetween('progress_percentage', [75.01, 100])->count(),
        ];

        return [
            'course_id' => $course->id,
            'title' => $course->title_ar,
            'total_enrollments' => $totalEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'completion_rate' => $completionRate,
            'average_progress' => $averageProgress,
            'reviews_count' => $reviewsCount,
            'average_rating' => $averageRating,
            'total_lessons' => $lessonsCount,
            'total_duration_minutes' => $totalDurationMinutes,
            'total_lesson_completions' => $totalLessonCompletions,
            'progress_distribution' => $distribution,
        ];
    }

    /**
     * Get aggregate analytics across all courses taught by an instructor.
     */
    public function getInstructorOverview(User $instructor): array
    {
        $courses = $instructor->authoredCourses()->with(['enrollments', 'lessons', 'approvedReviews'])->get();
        
        $totalCourses = $courses->count();
        $totalEnrollments = 0;
        $totalCompleted = 0;
        $allProgressValues = [];
        $allRatings = [];

        foreach ($courses as $c) {
            $totalEnrollments += $c->enrollments->count();
            $totalCompleted += $c->enrollments->where('status', 'COMPLETED')->count();
            foreach ($c->enrollments as $enr) {
                $allProgressValues[] = $enr->progress_percentage;
            }
            foreach ($c->approvedReviews as $rev) {
                $allRatings[] = $rev->rating;
            }
        }

        $overallAverageProgress = count($allProgressValues) > 0 ? round(array_sum($allProgressValues) / count($allProgressValues), 1) : 0;
        $overallAverageRating = count($allRatings) > 0 ? round(array_sum($allRatings) / count($allRatings), 1) : 0;
        $overallCompletionRate = $totalEnrollments > 0 ? round(($totalCompleted / $totalEnrollments) * 100, 1) : 0;

        return [
            'total_courses' => $totalCourses,
            'total_enrollments' => $totalEnrollments,
            'total_completed' => $totalCompleted,
            'overall_completion_rate' => $overallCompletionRate,
            'overall_average_progress' => $overallAverageProgress,
            'overall_average_rating' => $overallAverageRating,
        ];
    }
}
