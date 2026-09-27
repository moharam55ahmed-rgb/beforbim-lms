<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\CourseAnnouncement\Models\CourseAnnouncement;
use App\Modules\CourseDiscussion\Models\CourseDiscussion;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Services\EnrollmentService;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonContent;
use App\Modules\Media\Models\LessonResource;
use App\Modules\Media\Services\MediaSecurityService;
use App\Modules\Progress\Services\LessonProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningExperienceAndPlayerTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $otherStudent;
    protected User $instructor;
    protected User $admin;
    protected Course $course;
    protected CourseSection $section1;
    protected CourseSection $section2;
    protected Lesson $previewLesson;
    protected Lesson $privateVideoLesson;
    protected Lesson $privateTextLesson;
    protected LessonResource $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name_ar' => 'مسؤول', 'display_name_en' => 'Admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student'], ['display_name_ar' => 'طالب', 'display_name_en' => 'Student']);
        $instructorRole = Role::firstOrCreate(['name' => 'instructor'], ['display_name_ar' => 'مدرب', 'display_name_en' => 'Instructor']);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->sync([$studentRole->id]);

        $this->otherStudent = User::factory()->create(['status' => 'active']);
        $this->otherStudent->roles()->sync([$studentRole->id]);

        $this->instructor = User::factory()->create(['status' => 'active']);
        $this->instructor->roles()->sync([$instructorRole->id]);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->sync([$adminRole->id]);

        $category = Category::create([
            'name_ar' => 'هندسة مدنية وإنشائية',
            'name_en' => 'Civil & Structural Engineering',
            'slug' => 'civil-structural-' . uniqid(),
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دبلومة التصميم الإنشائي ونمذجة الـ BIM',
            'title_en' => 'Structural Design & BIM Diploma',
            'slug' => 'structural-bim-diploma-' . uniqid(),
            'price' => 1200.00,
            'currency' => 'SAR',
            'status' => 'APPROVED',
            'published_at' => now(),
            'preview_enabled' => true,
        ]);

        // Section 1
        $this->section1 = CourseSection::create([
            'course_id' => $this->course->id,
            'title_ar' => 'الوحدة الأولى: مدخل النمذجة الهندسية',
            'title_en' => 'Module 1: BIM Introduction',
            'order_index' => 1,
        ]);

        // Lesson 1: Free Preview
        $this->previewLesson = Lesson::create([
            'section_id' => $this->section1->id,
            'title_ar' => 'المحاضرة 1: نظرة عامة ومفاهيم الـ BIM',
            'title_en' => 'Lecture 1: BIM Overview',
            'lesson_type' => 'video',
            'duration_seconds' => 900,
            'order_index' => 1,
            'is_preview' => true,
        ]);

        LessonContent::create([
            'lesson_id' => $this->previewLesson->id,
            'title' => 'محتوى فيديو المحاضرة الأولى',
            'type' => 'video',
            'video_asset_id' => 'asset-intro-1',
            'video_hls_url' => 'https://stream.beforbim.com/intro.m3u8',
            'ordering' => 1,
            'visibility_status' => 'visible',
            'preview_availability' => true,
        ]);

        // Lesson 2: Private Video Lesson
        $this->privateVideoLesson = Lesson::create([
            'section_id' => $this->section1->id,
            'title_ar' => 'المحاضرة 2: نمذجة العناصر الخرسانية المسلحة',
            'title_en' => 'Lecture 2: Reinforced Concrete Modeling',
            'lesson_type' => 'video',
            'duration_seconds' => 1800,
            'order_index' => 2,
            'is_preview' => false,
        ]);

        LessonContent::create([
            'lesson_id' => $this->privateVideoLesson->id,
            'title' => 'فيديو المحاضرة الإنشائية',
            'type' => 'video',
            'video_asset_id' => 'asset-concrete-2',
            'ordering' => 1,
            'visibility_status' => 'visible',
        ]);

        $this->resource = LessonResource::create([
            'lesson_id' => $this->privateVideoLesson->id,
            'title_ar' => 'ملف الأوتوكاد والريفت الإنشائي',
            'title_en' => 'Structural CAD & Revit Model',
            'file_name' => 'structural_model.rvt',
            'file_path' => 'courses/resources/structural_model.rvt',
            'file_extension' => 'rvt',
            'file_size_bytes' => 45000000,
            'mime_type' => 'application/octet-stream',
            'is_downloadable' => true,
        ]);

        // Section 2
        $this->section2 = CourseSection::create([
            'course_id' => $this->course->id,
            'title_ar' => 'الوحدة الثانية: المواصفات والأكواد',
            'order_index' => 2,
        ]);

        // Lesson 3: Private Text Lesson
        $this->privateTextLesson = Lesson::create([
            'section_id' => $this->section2->id,
            'title_ar' => 'المحاضرة 3: معايير الكود السعودي للمنشآت SBC',
            'lesson_type' => 'text',
            'duration_seconds' => 600,
            'order_index' => 1,
            'is_preview' => false,
        ]);

        LessonContent::create([
            'lesson_id' => $this->privateTextLesson->id,
            'title' => 'مرجع كود البناء السعودي SBC 304',
            'type' => 'text',
            'document_markdown' => 'تفاصيل اشتراطات الكود السعودي للخرسانة المسلحة وتطبيقاتها في الـ BIM.',
            'ordering' => 1,
            'visibility_status' => 'visible',
        ]);
    }

    public function test_guest_can_access_preview_lesson_but_blocked_from_private_lesson(): void
    {
        // Guest visits preview lesson -> 200 OK
        $previewResponse = $this->get(route('learn.player', [$this->course->slug, $this->previewLesson->id]));
        $previewResponse->assertOk();
        $previewResponse->assertSee($this->previewLesson->title_ar);
        $previewResponse->assertSee('معاينة مجانية');

        // Guest visits private lesson -> 403 Forbidden
        $privateResponse = $this->get(route('learn.player', [$this->course->slug, $this->privateVideoLesson->id]));
        $privateResponse->assertForbidden();
    }

    public function test_enrolled_student_has_full_course_player_access(): void
    {
        // Enroll student
        app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Enrolled for diploma');

        $response = $this->actingAs($this->student)->get(route('learn.player', [$this->course->slug, $this->privateVideoLesson->id]));
        $response->assertOk();
        $response->assertSee($this->privateVideoLesson->title_ar);
        $response->assertSee('ملف الأوتوكاد والريفت الإنشائي');
        $response->assertSee('تحديد كمكتمل');
    }

    public function test_unenrolled_student_cannot_access_private_lesson_or_download_resource(): void
    {
        // Unenrolled student visiting private lesson -> 403
        $response = $this->actingAs($this->otherStudent)->get(route('learn.player', [$this->course->slug, $this->privateVideoLesson->id]));
        $response->assertForbidden();

        // Unenrolled student attempting to download private engineering file -> 403
        $downloadResponse = $this->actingAs($this->otherStudent)->get(route('learn.resource_download', $this->resource->id));
        $downloadResponse->assertForbidden();
    }

    public function test_enrolled_student_can_download_private_engineering_resource(): void
    {
        app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Enrolled student download test');

        $response = $this->actingAs($this->student)->get(route('learn.resource_download', $this->resource->id));
        $response->assertOk();
    }

    public function test_lesson_progress_tracking_and_course_percentage_calculation(): void
    {
        $enrollment = app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Test progress tracking');

        $this->assertEquals(0.00, (float) $enrollment->fresh()->progress_percentage);

        // 1. Save video playback progress
        $progressRes = $this->actingAs($this->student)->postJson(route('learn.progress', $this->previewLesson->id), [
            'position' => 300,
            'watch_seconds' => 120,
        ]);
        $progressRes->assertOk();

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $this->student->id,
            'lesson_id' => $this->previewLesson->id,
            'last_playback_position_seconds' => 300,
        ]);

        // 2. Complete Lesson 1
        $completeRes = $this->actingAs($this->student)->postJson(route('learn.toggle_complete', $this->previewLesson->id));
        $completeRes->assertOk();
        $completeRes->assertJson([
            'is_completed' => true,
        ]);

        // 1 out of 3 lessons completed -> 33.33%
        $this->assertEquals(33.33, round((float) $enrollment->fresh()->progress_percentage, 2));

        // 3. Complete Lesson 2 & 3
        $this->actingAs($this->student)->postJson(route('learn.toggle_complete', $this->privateVideoLesson->id));
        $this->actingAs($this->student)->postJson(route('learn.toggle_complete', $this->privateTextLesson->id));

        // 3 out of 3 lessons completed -> 100% and completed_at is recorded
        $freshEnrollment = $enrollment->fresh();
        $this->assertEquals(100.00, (float) $freshEnrollment->progress_percentage);
        $this->assertNotNull($freshEnrollment->completed_at);
    }

    public function test_course_discussions_and_announcements_in_learning_experience(): void
    {
        app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Discussions test');

        // 1. Instructor creates announcement
        CourseAnnouncement::create([
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'تحديث ملفات المشروعات التطبيقية',
            'content' => 'تم تحديث روابط ملفات الريفت للنسخة 2026 لجميع المشتركين.',
            'published_at' => now(),
        ]);

        // 2. Student posts discussion question
        $questionResponse = $this->actingAs($this->student)->post(route('learn.discussions.store', $this->course->id), [
            'lesson_id' => $this->previewLesson->id,
            'message' => 'هل يلزم تثبيت إصدار Navisworks 2026 لمتابعة هذا الدرس؟',
        ]);
        $questionResponse->assertSessionHas('status');

        $discussion = CourseDiscussion::where('course_id', $this->course->id)->whereNull('parent_id')->first();
        $this->assertNotNull($discussion);
        $this->assertEquals('هل يلزم تثبيت إصدار Navisworks 2026 لمتابعة هذا الدرس؟', $discussion->message);

        // 3. Instructor replies to the student
        $replyResponse = $this->actingAs($this->instructor)->post(route('learn.discussions.store', $this->course->id), [
            'parent_id' => $discussion->id,
            'message' => 'أهلاً يا بشمهندس، يمكنك العمل على إصدارات 2024 فما فوق دون أي مشكلة.',
        ]);
        $replyResponse->assertSessionHas('status');

        $this->assertDatabaseHas('course_discussions', [
            'parent_id' => $discussion->id,
            'user_id' => $this->instructor->id,
        ]);

        // 4. View player page and verify announcements and discussions are visible
        $playerView = $this->actingAs($this->student)->get(route('learn.player', [$this->course->slug, $this->previewLesson->id]));
        $playerView->assertOk();
        $playerView->assertSee('تحديث ملفات المشروعات التطبيقية');
        $playerView->assertSee('هل يلزم تثبيت إصدار Navisworks 2026 لمتابعة هذا الدرس؟');
        $playerView->assertSee('أهلاً يا بشمهندس، يمكنك العمل على إصدارات 2024 فما فوق دون أي مشكلة.');
    }

    public function test_signed_media_stream_tokens_and_security_validation(): void
    {
        $securityService = app(MediaSecurityService::class);
        $content = $this->previewLesson->contents()->first();

        // 1. Generate valid token
        $validToken = hash_hmac('sha256', "media-{$content->id}-user-{$this->student->id}-" . now()->format('YmdH'), config('app.key'));
        $this->assertTrue($securityService->validateMediaToken($validToken, $content->id, $this->student->id));

        // 2. Reject forged/invalid token
        $this->assertFalse($securityService->validateMediaToken('invalid-forged-token', $content->id, $this->student->id));

        // 3. Request video stream endpoint with valid token
        $streamResponse = $this->actingAs($this->student)->getJson(route('learn.video_stream', [
            'content' => $content->id,
            'token' => $validToken,
        ]));
        $streamResponse->assertOk();
        $streamResponse->assertJsonStructure(['stream_url', 'provider']);

        // 4. Request with invalid token -> 403 Forbidden
        $unauthorizedResponse = $this->actingAs($this->student)->getJson(route('learn.video_stream', [
            'content' => $content->id,
            'token' => 'fake-token-xyz',
        ]));
        $unauthorizedResponse->assertForbidden();
    }
}
