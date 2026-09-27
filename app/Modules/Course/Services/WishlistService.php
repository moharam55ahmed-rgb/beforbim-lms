<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class WishlistService
{
    /**
     * Add a course to student's wishlist.
     */
    public function add(User $user, Course $course): Wishlist
    {
        $this->ensureCanUseWishlist($user);

        return Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    /**
     * Remove a course from student's wishlist.
     */
    public function remove(User $user, Course $course): bool
    {
        $this->ensureCanUseWishlist($user);

        return (bool) Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->delete();
    }

    /**
     * Toggle wishlist state (add if absent, remove if present).
     * Returns true if added, false if removed.
     */
    public function toggle(User $user, Course $course): bool
    {
        $this->ensureCanUseWishlist($user);

        $existing = Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return false;
        }

        Wishlist::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        return true;
    }

    /**
     * Check if a course is in student's wishlist.
     */
    public function has(User $user, Course $course): bool
    {
        return Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
    }

    /**
     * Get all wishlisted courses for a student.
     */
    public function getStudentWishlist(User $user): Collection
    {
        $this->ensureCanUseWishlist($user);

        return Course::whereHas('wishlists', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['instructor', 'category'])->get();
    }

    /**
     * Validate that the user is permitted to use the wishlist (student role or account).
     */
    protected function ensureCanUseWishlist(User $user): void
    {
        if ($user->isSuspended() || $user->isBlocked()) {
            throw new InvalidArgumentException('الحساب مقيد ولا يمكنه استخدام قائمة الرغبات.');
        }

        // Must not be admin or instructor acting as instructor, or must have student role / normal user
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            throw new InvalidArgumentException('خاصية قائمة الرغبات مخصصة للطلاب فقط.');
        }
    }
}
