<?php

namespace App\Modules\Course\Policies;

use App\Models\User;
use App\Modules\Course\Models\Course;

class CoursePolicy
{
    /**
     * Determine whether the user can view the course.
     */
    public function view(?User $user, Course $course): bool
    {
        if ($course->status === 'APPROVED' && $course->published_at !== null) {
            return true;
        }

        if (! $user) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $course->hasInstructor($user);
    }

    /**
     * Determine whether the user can update the course.
     */
    public function update(User $user, Course $course): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole(['admin', 'instructor']) && $course->hasInstructor($user);
    }

    /**
     * Determine whether the user can audit and publish the course.
     * Note: Instructors can NEVER publish directly; requires admin audit.
     */
    public function publish(User $user, Course $course): bool
    {
        return $user->hasPermission('courses.audit_publish');
    }

    /**
     * Determine whether the user can delete the course.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->hasRole('super_admin');
    }
}
