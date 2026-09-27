<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\Assignment\Models\Assignment;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Progress\Models\LessonProgress;
use App\Modules\User\Services\UserActivityService;
use Illuminate\Support\Collection;

class StudentDashboardService
{
    public function __construct(
        protected UserActivityService $userActivityService
    ) {}

    /**
     * Get active enrolled courses with progress, instructor, and curriculum counts.
     */
    public function getMyEnrolledCourses(User $user, int $limit = 6): Collection
    {
        return $user->enrollments()
            ->with(['course.instructor', 'course.category', 'course.lessons'])
            ->where('status', 'ACTIVE')
            ->latest('enrolled_at')
            ->take($limit)
            ->get();
    }

    /**
     * Get the "Continue Learning" widget data: most relevant course + next unfinished lesson.
     */
    public function getContinueLearning(User $user): ?array
    {
        $enrollment = $user->enrollments()
            ->with(['course.lessons'])
            ->where('status', 'ACTIVE')
            ->where('progress_percentage', '<', 100)
            ->latest('updated_at')
            ->first();

        if (! $enrollment || ! $enrollment->course) {
            // Fallback to latest enrolled course
            $enrollment = $user->enrollments()
                ->with(['course.lessons'])
                ->where('status', 'ACTIVE')
                ->latest('enrolled_at')
                ->first();

            if (! $enrollment || ! $enrollment->course) {
                return null;
            }
        }

        $course = $enrollment->course;
        $lessons = $course->lessons;

        if ($lessons->isEmpty()) {
            return [
                'course' => $course,
                'next_lesson' => null,
                'progress_percentage' => (float) $enrollment->progress_percentage,
            ];
        }

        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $nextLesson = $lessons->first(fn ($l) => ! in_array($l->id, $completedLessonIds)) ?? $lessons->first();

        return [
            'course' => $course,
            'next_lesson' => $nextLesson,
            'progress_percentage' => (float) $enrollment->progress_percentage,
            'completed_lessons_count' => count($completedLessonIds),
            'total_lessons_count' => $lessons->count(),
        ];
    }

    /**
     * Calculate overall learning statistics for the student.
     */
    public function getCourseProgressStats(User $user): array
    {
        $enrollments = $user->enrollments()->where('status', 'ACTIVE')->get();
        $totalCourses = $enrollments->count();
        $completedCourses = $enrollments->where('progress_percentage', '>=', 100.00)->count();
        $inProgressCourses = $totalCourses - $completedCourses;
        $averageProgress = $totalCourses > 0 ? round($enrollments->avg('progress_percentage'), 1) : 0;

        return [
            'total_enrolled' => $totalCourses,
            'completed_courses' => $completedCourses,
            'in_progress' => $inProgressCourses,
            'average_progress' => $averageProgress,
        ];
    }

    /**
     * Get pending engineering assignments (unsubmitted or revisions requested).
     */
    public function getPendingAssignments(User $user, int $limit = 5): Collection
    {
        $enrolledCourseIds = $user->enrollments()
            ->where('status', 'ACTIVE')
            ->pluck('course_id');

        return Assignment::query()
            ->whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->whereDoesntHave('submissions', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereIn('status', ['GRADED', 'APPROVED']);
            })
            ->orderBy('due_date')
            ->take($limit)
            ->get();
    }

    /**
     * Get upcoming or available assessments and exams for enrolled courses.
     */
    public function getUpcomingAssessments(User $user, int $limit = 5): Collection
    {
        $enrolledCourseIds = $user->enrollments()
            ->where('status', 'ACTIVE')
            ->pluck('course_id');

        return Assessment::query()
            ->whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'attempts' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->latest();
            }])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get certificates earned by the student.
     */
    public function getCertificates(User $user, int $limit = 6): Collection
    {
        return Certificate::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with('course.instructor')
            ->latest('issued_at')
            ->take($limit)
            ->get();
    }

    /**
     * Build the entire student dashboard payload with all requested widgets.
     */
    public function getDashboardData(User $user): array
    {
        return [
            'user' => $user,
            'continue_learning' => $this->getContinueLearning($user),
            'enrolled_courses' => $this->getMyEnrolledCourses($user, 6),
            'progress_stats' => $this->getCourseProgressStats($user),
            'pending_assignments' => $this->getPendingAssignments($user, 5),
            'upcoming_assessments' => $this->getUpcomingAssessments($user, 5),
            'certificates' => $this->getCertificates($user, 6),
            'active_devices' => $this->userActivityService->getActiveDevices($user),
            'last_login' => $this->userActivityService->getLastLogin($user),
        ];
    }
}
