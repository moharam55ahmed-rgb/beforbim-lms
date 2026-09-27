<?php

namespace App\Modules\Lesson\Policies;

use App\Models\User;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\Lesson\Models\Lesson;

class LessonPolicy
{
    public function __construct(
        protected CourseAccessService $accessService = new CourseAccessService
    ) {}

    /**
     * Determine whether the user or guest can access and view the lesson.
     */
    public function view(?User $user, Lesson $lesson): bool
    {
        return $this->accessService->canAccessLesson($user, $lesson);
    }

    /**
     * Determine whether the user can access and download private lesson resources.
     * Note: Preview guests can NEVER access private resources.
     */
    public function viewResource(?User $user, Lesson $lesson): bool
    {
        if (! $user) {
            return false;
        }

        $course = $lesson->section?->course;
        if (! $course) {
            return false;
        }

        return $this->accessService->canAccessCourse($user, $course);
    }
}
