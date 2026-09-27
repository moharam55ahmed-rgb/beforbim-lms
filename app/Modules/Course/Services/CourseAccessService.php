<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonResource;
use Illuminate\Auth\Access\AuthorizationException;

class CourseAccessService
{
    /**
     * Determine if a user has full access to a course.
     */
    public function canAccessCourse(?User $user, Course $course): bool
    {
        if (! $user) {
            return false;
        }

        // Check account restrictions
        if ($this->isAccountRestricted($user)) {
            return false;
        }

        // Administrative bypass
        if ($this->hasAdministrativePrivileges($user)) {
            return true;
        }

        // Course instructors & co-instructors
        if ($course->hasInstructor($user)) {
            return true;
        }

        // Active, non-expired enrollment
        return $user->isEnrolledIn($course);
    }

    /**
     * Determine if a user can access and view a specific lesson.
     */
    public function canAccessLesson(?User $user, Lesson $lesson): bool
    {
        // Check account restrictions if user is logged in
        if ($user && $this->isAccountRestricted($user)) {
            return false;
        }

        // Free preview lesson access
        if ($lesson->canPreview()) {
            return true;
        }

        // Non-preview lessons require an active authenticated user
        if (! $user) {
            return false;
        }

        // Administrative privileges
        if ($this->hasAdministrativePrivileges($user)) {
            return true;
        }

        $course = $lesson->section?->course;
        if (! $course) {
            return false;
        }

        // Instructor ownership
        if ($course->hasInstructor($user)) {
            return true;
        }

        // Enrolled student
        return $user->isEnrolledIn($course);
    }

    /**
     * Determine if a user can download a course / lesson resource.
     * Downloadable files are strictly protected for enrolled students & instructors.
     */
    public function canDownloadResource(?User $user, LessonResource $resource): bool
    {
        if (! $resource->is_downloadable) {
            return false;
        }

        if (! $user || $this->isAccountRestricted($user)) {
            return false;
        }

        if ($this->hasAdministrativePrivileges($user)) {
            return true;
        }

        $course = $resource->lesson?->section?->course;
        if (! $course) {
            return false;
        }

        if ($course->hasInstructor($user)) {
            return true;
        }

        return $user->isEnrolledIn($course);
    }

    /**
     * Determine if a user can manage course settings, curriculum, and contents.
     */
    public function canManageCourse(User $user, Course $course): bool
    {
        if ($this->isAccountRestricted($user)) {
            return false;
        }

        if ($this->hasAdministrativePrivileges($user)) {
            return true;
        }

        return $course->hasInstructor($user);
    }

    /**
     * Determine if a user can participate in course community discussions.
     */
    public function canParticipateInDiscussion(User $user, Course $course): bool
    {
        if ($this->isAccountRestricted($user)) {
            return false;
        }

        if ($this->hasAdministrativePrivileges($user)) {
            return true;
        }

        if ($course->hasInstructor($user)) {
            return true;
        }

        return $user->isEnrolledIn($course);
    }

    /**
     * Centralized authorization check that throws an AuthorizationException if access is denied.
     *
     * @throws AuthorizationException
     */
    public function authorizeLessonAccess(?User $user, Lesson $lesson): void
    {
        if ($user && $this->isAccountRestricted($user)) {
            throw new AuthorizationException('تم تعليق أو حظر حسابك. يرجى التواصل مع الدعم الفني لحل المشكلة.');
        }

        if (! $this->canAccessLesson($user, $lesson)) {
            if (! $user) {
                throw new AuthorizationException('يرجى تسجيل الدخول والاشتراك في الدورة لتتمكن من متابعة هذا الدرس.');
            }

            throw new AuthorizationException('هذا الدرس مخصص للمشتركين في هذه الدورة الهندسية فقط.');
        }
    }

    /**
     * Check if a user's account is suspended or blocked.
     */
    public function isAccountRestricted(User $user): bool
    {
        return $user->isSuspended() || $user->isBlocked() || $user->status !== 'active';
    }

    /**
     * Check if a user has platform-level administrative privileges.
     */
    protected function hasAdministrativePrivileges(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin');
    }
}
