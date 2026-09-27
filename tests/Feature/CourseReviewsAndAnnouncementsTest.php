<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Enrollment\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseReviewsAndAnnouncementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instructor;

    protected User $student;

    protected User $otherStudent;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name_ar' => 'مسؤول', 'display_name_en' => 'Admin']);
        $instructorRole = Role::firstOrCreate(['name' => 'instructor'], ['display_name_ar' => 'مدرب', 'display_name_en' => 'Instructor']);
        $studentRole = Role::firstOrCreate(['name' => 'student'], ['display_name_ar' => 'طالب', 'display_name_en' => 'Student']);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->instructor = User::factory()->create(['status' => 'active']);
        $this->instructor->roles()->sync([$instructorRole->id]);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->sync([$studentRole->id]);

        $this->otherStudent = User::factory()->create(['status' => 'active']);
        $this->otherStudent->roles()->sync([$studentRole->id]);

        $category = Category::create([
            'name_ar' => 'التصميم الإنشائي BIM',
            'name_en' => 'Structural BIM',
            'slug' => 'structural-bim',
        ]);

        $this->course = Course::create([
            'uuid' => (string) Str::uuid(),
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دورة دبلوم التصميم الإنشائي عبر Revit Structure',
            'title_en' => 'Revit Structure Diploma',
            'slug' => 'revit-structure-diploma',
            'short_description_ar' => 'وصف مختصر',
            'description_ar' => 'وصف كامل',
            'price' => 450.00,
            'status' => 'APPROVED',
            'published_at' => now(),
        ]);
    }

    public function test_enrolled_student_can_submit_course_review(): void
    {
        // Enroll student
        Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'source' => 'DIRECT_PURCHASE',
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($this->student)->post(route('courses.reviews.store', $this->course), [
            'rating' => 5,
            'review_text' => 'دورة ممتازة جداً وشرح المهندس احترافي ومفصل في تسليح العناصر الإنشائية.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('course_reviews', [
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'rating' => 5,
            'status' => 'pending',
        ]);
    }

    public function test_non_enrolled_student_cannot_submit_review(): void
    {
        $response = $this->actingAs($this->otherStudent)->post(route('courses.reviews.store', $this->course), [
            'rating' => 4,
            'review_text' => 'محاولة تقييم بدون اشتراك',
        ]);

        $response->assertSessionHasErrors('course');
        $this->assertDatabaseMissing('course_reviews', [
            'student_id' => $this->otherStudent->id,
        ]);
    }

    public function test_admin_can_moderate_and_approve_review_and_rating_updates(): void
    {
        $review = CourseReview::create([
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'rating' => 5,
            'review_text' => 'محتوى رائع وتطبيقات عملية',
            'status' => 'pending',
        ]);

        // Before approval, average rating is 0
        $this->assertEquals(0.0, $this->course->fresh()->average_rating);

        $response = $this->actingAs($this->admin)->post(route('admin.reviews.moderate', $review), [
            'status' => 'approved',
            'admin_feedback' => 'تم التحقق من الطالب والاعتماد',
        ]);

        $response->assertRedirect();
        $review->refresh();
        $this->assertEquals('approved', $review->status);

        // After approval, course average rating is 5.0
        $this->assertEquals(5.0, $this->course->fresh()->average_rating);
        $this->assertEquals(1, $this->course->fresh()->reviews_count);
    }

    public function test_instructor_can_publish_announcement_and_notify_enrolled_students(): void
    {
        // Enroll student in course
        Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'source' => 'DIRECT_PURCHASE',
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($this->instructor)->post(route('courses.announcements.store', $this->course), [
            'title' => 'تحديث مهم: رفع ملفات عائلة الريفيت الخاصة بالدرس الخامس',
            'content' => 'تمت إضافة ملفات RFA جديدة في تبويب المرفقات الهندسية الخاصة بمشروع البرج التجاري.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('course_announcements', [
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'تحديث مهم: رفع ملفات عائلة الريفيت الخاصة بالدرس الخامس',
        ]);

        // Check announcement appears in announcements view
        $viewResponse = $this->actingAs($this->student)->get(route('courses.announcements.index', $this->course));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('تحديث مهم: رفع ملفات عائلة الريفيت');
    }
}
