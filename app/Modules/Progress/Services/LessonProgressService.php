<?php

namespace App\Modules\Progress\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Progress\Models\LessonProgress;
use Illuminate\Support\Facades\DB;

class LessonProgressService
{
    /**
     * Get or initialize progress record for a user and lesson.
     */
    public function getOrCreateProgress(User $user, Lesson $lesson): LessonProgress
    {
        $course = $lesson->section?->course;
        $enrollment = $course ? Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'ACTIVE')
            ->first() : null;

        return LessonProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'enrollment_id' => $enrollment?->id,
                'is_completed' => false,
                'last_playback_position_seconds' => 0,
                'total_watch_seconds' => 0,
            ]
        );
    }

    /**
     * Record video playback position and cumulative watch seconds.
     */
    public function recordPlayback(User $user, Lesson $lesson, int $playbackPosition, int $watchSeconds = 0): LessonProgress
    {
        $progress = $this->getOrCreateProgress($user, $lesson);

        $progress->update([
            'last_playback_position_seconds' => $playbackPosition,
            'total_watch_seconds' => $progress->total_watch_seconds + max(0, $watchSeconds),
        ]);

        return $progress;
    }

    /**
     * Toggle lesson completion status and recalculate course progress percentage.
     */
    public function toggleCompletion(User $user, Lesson $lesson): array
    {
        return DB::transaction(function () use ($user, $lesson) {
            $progress = $this->getOrCreateProgress($user, $lesson);
            $newCompletedState = ! $progress->is_completed;

            $progress->update([
                'is_completed' => $newCompletedState,
                'completed_at' => $newCompletedState ? now() : null,
            ]);

            $course = $lesson->section?->course;
            $progressPercentage = $course ? $this->calculateCourseProgress($user, $course) : 0.0;

            return [
                'is_completed' => $newCompletedState,
                'lesson_id' => $lesson->id,
                'course_progress_percentage' => $progressPercentage,
            ];
        });
    }

    /**
     * Mark lesson completed directly.
     */
    public function markCompleted(User $user, Lesson $lesson): array
    {
        return DB::transaction(function () use ($user, $lesson) {
            $progress = $this->getOrCreateProgress($user, $lesson);

            if (! $progress->is_completed) {
                $progress->update([
                    'is_completed' => true,
                    'completed_at' => now(),
                ]);
            }

            $course = $lesson->section?->course;
            $progressPercentage = $course ? $this->calculateCourseProgress($user, $course) : 0.0;

            return [
                'is_completed' => true,
                'lesson_id' => $lesson->id,
                'course_progress_percentage' => $progressPercentage,
            ];
        });
    }

    /**
     * Calculate and sync total course progress percentage for a student.
     */
    public function calculateCourseProgress(User $user, Course $course): float
    {
        $totalLessons = $course->lessons()->count();

        if ($totalLessons === 0) {
            return 100.00;
        }

        $lessonIds = $course->lessons()->pluck('lessons.id');

        $completedCount = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();

        $percentage = round(($completedCount / $totalLessons) * 100, 2);

        // Update active enrollment record if exists
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'ACTIVE')
            ->first();

        if ($enrollment) {
            $updateData = ['progress_percentage' => $percentage];
            if ($percentage >= 100.00 && ! $enrollment->completed_at) {
                $updateData['completed_at'] = now();
            }
            $enrollment->update($updateData);
        }

        return $percentage;
    }

    /**
     * Get comprehensive progress metadata for course player.
     */
    public function getCourseProgressData(User $user, Course $course): array
    {
        $lessonIds = $course->lessons()->pluck('lessons.id');

        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = count($lessonIds);
        $percentage = $totalLessons > 0
            ? round((count($completedLessonIds) / $totalLessons) * 100, 2)
            : 0.0;

        return [
            'total_lessons' => $totalLessons,
            'completed_count' => count($completedLessonIds),
            'completed_lesson_ids' => $completedLessonIds,
            'percentage' => $percentage,
        ];
    }
}
