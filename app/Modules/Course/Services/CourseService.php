<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Notification\Events\CoursePublished;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseService
{
    /**
     * Create a new course authored by an instructor.
     */
    public function createCourse(array $data, User $instructor): Course
    {
        return DB::transaction(function () use ($data, $instructor) {
            $course = new Course;
            $course->instructor_id = $instructor->id;
            $course->category_id = $data['category_id'] ?? null;
            $course->title_ar = $data['title_ar'];
            $course->title_en = $data['title_en'] ?? null;
            $course->slug = Str::slug($data['title_en'] ?? $data['title_ar']).'-'.Str::random(5);
            $course->short_description_ar = $data['short_description_ar'] ?? null;
            $course->description_ar = $data['description_ar'] ?? null;
            $course->level = $data['level'] ?? 'ALL_LEVELS';
            $course->price = $data['price'] ?? 0;
            $course->sale_price = $data['sale_price'] ?? null;
            $course->currency = $data['currency'] ?? 'USD';
            $course->thumbnail_url = $data['thumbnail_url'] ?? null;
            $course->promo_video_url = $data['promo_video_url'] ?? null;
            $course->software_requirements = $data['software_requirements'] ?? [];
            $course->prerequisites = $data['prerequisites'] ?? [];
            $course->learning_outcomes = $data['learning_outcomes'] ?? [];
            $course->status = 'DRAFT';
            $course->save();

            return $course;
        });
    }

    /**
     * Update existing course details.
     */
    public function updateCourse(Course $course, array $data): Course
    {
        return DB::transaction(function () use ($course, $data) {
            $course->update([
                'category_id' => $data['category_id'] ?? $course->category_id,
                'title_ar' => $data['title_ar'] ?? $course->title_ar,
                'title_en' => $data['title_en'] ?? $course->title_en,
                'short_description_ar' => $data['short_description_ar'] ?? $course->short_description_ar,
                'description_ar' => $data['description_ar'] ?? $course->description_ar,
                'level' => $data['level'] ?? $course->level,
                'price' => $data['price'] ?? $course->price,
                'sale_price' => array_key_exists('sale_price', $data) ? $data['sale_price'] : $course->sale_price,
                'currency' => $data['currency'] ?? $course->currency,
                'thumbnail_url' => $data['thumbnail_url'] ?? $course->thumbnail_url,
                'promo_video_url' => $data['promo_video_url'] ?? $course->promo_video_url,
                'software_requirements' => $data['software_requirements'] ?? $course->software_requirements,
                'prerequisites' => $data['prerequisites'] ?? $course->prerequisites,
                'learning_outcomes' => $data['learning_outcomes'] ?? $course->learning_outcomes,
            ]);

            return $course;
        });
    }

    /**
     * Submit a draft course for admin review and approval.
     */
    public function submitForApproval(Course $course): bool
    {
        if ($course->status !== 'DRAFT' && $course->status !== 'REJECTED') {
            return false;
        }

        $course->update([
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
            'rejection_feedback' => null,
        ]);

        return true;
    }

    /**
     * Approve a submitted course and publish it.
     */
    public function approveCourse(Course $course, User $admin): Course
    {
        return DB::transaction(function () use ($course, $admin) {
            $course->update([
                'status' => 'APPROVED',
                'approved_at' => now(),
                'approved_by_user_id' => $admin->id,
                'published_at' => now(),
                'rejection_feedback' => null,
            ]);

            // Dispatch domain event
            event(new CoursePublished($course));

            return $course;
        });
    }

    /**
     * Reject a submitted course with feedback for the instructor.
     */
    public function rejectCourse(Course $course, string $feedback, User $admin): Course
    {
        return DB::transaction(function () use ($course, $feedback) {
            $course->update([
                'status' => 'REJECTED',
                'rejection_feedback' => $feedback,
            ]);

            return $course;
        });
    }

    /**
     * Soft delete a course.
     */
    public function deleteCourse(Course $course): bool
    {
        return (bool) $course->delete();
    }
}
