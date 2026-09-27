<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonResource;
use App\Modules\Notification\Events\CoursePublished;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CourseManagementAndCurriculumTest extends TestCase
{
    use DatabaseTransactions;

    protected User $instructor;
    protected User $admin;
    protected User $student;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $instructorRole = Role::firstOrCreate(['name' => 'instructor'], ['display_name_ar' => 'مدرب', 'display_name_en' => 'Instructor']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name_ar' => 'مسؤول', 'display_name_en' => 'Admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student'], ['display_name_ar' => 'طالب', 'display_name_en' => 'Student']);

        $this->instructor = User::factory()->create(['status' => 'active']);
        $this->instructor->roles()->sync([$instructorRole->id]);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->sync([$studentRole->id]);

        $this->category = Category::create([
            'name_ar' => 'هندسة الـ BIM والنمذجة',
            'name_en' => 'BIM Engineering',
            'slug' => 'bim-engineering',
            'is_active' => true,
        ]);
    }

    public function test_instructor_can_create_a_course_as_draft(): void
    {
        $response = $this->actingAs($this->instructor)->post(route('courses.store'), [
            'title_ar' => 'دبلومة الـ Revit المعماري المتطورة',
            'title_en' => 'Advanced Revit Architecture Diploma',
            'category_id' => $this->category->id,
            'level' => 'INTERMEDIATE',
            'price' => 250.00,
            'sale_price' => 199.00,
            'currency' => 'USD',
            'short_description_ar' => 'تعلم النمذجة المعمارية وحساب الكميات بدقة عالية.',
        ]);

        $course = Course::where('title_ar', 'دبلومة الـ Revit المعماري المتطورة')->first();

        $this->assertNotNull($course);
        $this->assertEquals('DRAFT', $course->status);
        $this->assertEquals($this->instructor->id, $course->instructor_id);
        $response->assertRedirect(route('courses.curriculum', $course->id));
    }

    public function test_instructor_can_add_sections_and_lessons_to_curriculum(): void
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'كورس التنسيق بواسطة Navisworks',
            'status' => 'DRAFT',
            'price' => 150,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        // Add section
        $sectionResponse = $this->actingAs($this->instructor)->post(route('courses.sections.store', $course->id), [
            'title_ar' => 'الفصل الأول: كشف التعارضات الهندسية (Clash Detection)',
            'description_ar' => 'شرح واجهة Navisworks Manage وقواعد التنسيق',
        ]);
        $sectionResponse->assertSessionHas('success');

        $section = CourseSection::where('course_id', $course->id)->first();
        $this->assertNotNull($section);
        $this->assertEquals('الفصل الأول: كشف التعارضات الهندسية (Clash Detection)', $section->title_ar);

        // Add lesson
        $lessonResponse = $this->actingAs($this->instructor)->post(route('courses.lessons.store', $section->id), [
            'title_ar' => 'المحاضرة 1: ضبط المصفوفات ومعايير الـ Tolerance',
            'lesson_type' => 'VIDEO',
            'duration_seconds' => 720,
            'is_preview_free' => true,
            'video_url' => 'https://vimeo.com/sample-clash',
        ]);
        $lessonResponse->assertSessionHas('success');

        $lesson = Lesson::where('section_id', $section->id)->first();
        $this->assertNotNull($lesson);
        $this->assertEquals('VIDEO', $lesson->lesson_type);
        $this->assertTrue($lesson->is_preview_free);
    }

    public function test_instructor_can_attach_downloadable_engineering_resource_to_lesson(): void
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'دورة النمذجة المتقدمة',
            'status' => 'DRAFT',
            'price' => 100,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        $section = CourseSection::create([
            'course_id' => $course->id,
            'title_ar' => 'قسم النماذج والمكتبات',
            'order_index' => 1,
        ]);

        $lesson = Lesson::create([
            'section_id' => $section->id,
            'title_ar' => 'درس تحميل عائلات Revit',
            'lesson_type' => 'DOCUMENT',
            'order_index' => 1,
        ]);

        $response = $this->actingAs($this->instructor)->post(route('courses.resources.store', $lesson->id), [
            'title_ar' => 'ملف عائلة الأبواب التخصصية',
            'file_name' => 'parametric-door.rfa',
            'file_path' => 'courses/resources/parametric-door.rfa',
            'file_extension' => 'rfa',
            'is_downloadable' => true,
        ]);

        $response->assertSessionHas('success');

        $resource = LessonResource::where('lesson_id', $lesson->id)->first();
        $this->assertNotNull($resource);
        $this->assertEquals('parametric-door.rfa', $resource->file_name);
        $this->assertTrue($resource->is_downloadable);
    }

    public function test_instructor_cannot_submit_empty_course_for_approval(): void
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'دورة فارغة بدون مناهج',
            'status' => 'DRAFT',
            'price' => 100,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        $response = $this->actingAs($this->instructor)->post(route('courses.submit', $course->id));

        $response->assertSessionHas('error');
        $this->assertEquals('DRAFT', $course->fresh()->status);
    }

    public function test_instructor_can_submit_completed_course_for_approval(): void
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'دورة BIM مكتملة الفصول',
            'status' => 'DRAFT',
            'price' => 100,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        $section = CourseSection::create([
            'course_id' => $course->id,
            'title_ar' => 'القسم التمهيدي',
            'order_index' => 1,
        ]);

        Lesson::create([
            'section_id' => $section->id,
            'title_ar' => 'الدرس الأول',
            'lesson_type' => 'VIDEO',
            'order_index' => 1,
        ]);

        $response = $this->actingAs($this->instructor)->post(route('courses.submit', $course->id));

        $response->assertSessionHas('success');
        $this->assertEquals('SUBMITTED', $course->fresh()->status);
        $this->assertNotNull($course->fresh()->submitted_at);
    }

    public function test_admin_can_approve_course_and_trigger_event(): void
    {
        Event::fake([CoursePublished::class]);

        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'دبلومة جاهزة للمراجعة',
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
            'price' => 200,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.courses.approve', $course->id));

        $response->assertRedirect(route('admin.courses.pending'));
        $course->refresh();

        $this->assertEquals('APPROVED', $course->status);
        $this->assertNotNull($course->approved_at);
        $this->assertNotNull($course->published_at);
        $this->assertEquals($this->admin->id, $course->approved_by_user_id);

        Event::assertDispatched(CoursePublished::class, function ($event) use ($course) {
            return $event->course->id === $course->id;
        });
    }

    public function test_admin_can_reject_course_with_feedback(): void
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'دورة تتطلب تعديلات فنية',
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
            'price' => 200,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.courses.reject', $course->id), [
            'rejection_feedback' => 'يرجى مراجعة جودة الصوت في الدرس الثاني وإضافة ملفات RVT.',
        ]);

        $response->assertRedirect(route('admin.courses.pending'));
        $course->refresh();

        $this->assertEquals('REJECTED', $course->status);
        $this->assertEquals('يرجى مراجعة جودة الصوت في الدرس الثاني وإضافة ملفات RVT.', $course->rejection_feedback);
    }

    public function test_student_cannot_create_or_approve_courses(): void
    {
        $createResponse = $this->actingAs($this->student)->post(route('courses.store'), [
            'title_ar' => 'محاولة اختراق الصلاحيات',
            'category_id' => $this->category->id,
            'level' => 'BEGINNER',
            'price' => 10,
        ]);
        $createResponse->assertForbidden();

        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'دورة اختبار الأمان',
            'status' => 'SUBMITTED',
            'price' => 100,
            'currency' => 'USD',
            'level' => 'ALL_LEVELS',
        ]);

        $approveResponse = $this->actingAs($this->student)->post(route('admin.courses.approve', $course->id));
        $approveResponse->assertForbidden();
    }

    public function test_admin_can_manage_categories(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name_ar' => 'نمذجة المنشآت المعدنية (Tekla & Revit)',
            'name_en' => 'Steel Structures BIM',
            'description_ar' => 'تصنيف يختص بنمذجة وتفصيل الهياكل الفولاذية والوصلات المعدنية.',
            'display_order' => 5,
        ]);

        $response->assertSessionHas('success');

        $category = Category::where('name_ar', 'نمذجة المنشآت المعدنية (Tekla & Revit)')->first();
        $this->assertNotNull($category);
        $this->assertEquals('steel-structures-bim', $category->slug);

        // Delete category
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category->id));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
