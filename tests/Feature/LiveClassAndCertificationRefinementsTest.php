<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentQuestion;
use App\Modules\Assignment\Models\Assignment;
use App\Modules\Assignment\Models\AssignmentRubric;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\Assignment\Services\AssignmentService;
use App\Modules\Category\Models\Category;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Certificate\Models\CertificateTemplate;
use App\Modules\Certificate\Services\CertificatePdfService;
use App\Modules\Course\Models\Course;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\LiveClass\Models\LiveClass;
use App\Modules\LiveClass\Models\LiveClassAttendance;
use App\Modules\LiveClass\Services\LiveClassProviderManager;
use App\Modules\LiveClass\Services\LiveClassService;
use App\Modules\LiveClass\Services\Providers\GoogleMeetProvider;
use App\Modules\LiveClass\Services\Providers\ZoomMeetingProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveClassAndCertificationRefinementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $instructor;
    protected User $admin;
    protected Course $course;
    protected CourseSection $section;
    protected Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name_ar' => 'مسؤول', 'display_name_en' => 'Admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student'], ['display_name_ar' => 'طالب', 'display_name_en' => 'Student']);
        $instructorRole = Role::firstOrCreate(['name' => 'instructor'], ['display_name_ar' => 'مدرب', 'display_name_en' => 'Instructor']);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->sync([$studentRole->id]);

        $this->instructor = User::factory()->create(['status' => 'active']);
        $this->instructor->roles()->sync([$instructorRole->id]);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->sync([$adminRole->id]);

        $category = Category::create([
            'name_ar' => 'نمذجة BIM هندسية',
            'name_en' => 'BIM Engineering',
            'slug' => 'bim-eng-' . uniqid(),
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'ماجستير نمذجة معلومات البناء Revit + Navisworks',
            'title_en' => 'BIM Master: Revit + Navisworks',
            'slug' => 'bim-master-' . uniqid(),
            'price' => 2500.00,
            'currency' => 'SAR',
            'status' => 'APPROVED',
            'published_at' => now(),
        ]);

        $this->section = CourseSection::create([
            'course_id' => $this->course->id,
            'title_ar' => 'الوحدة الأولى: أساسيات نمذجة Revit',
            'order_index' => 1,
        ]);

        $this->lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title_ar' => 'مقدمة في Revit Architecture',
            'lesson_type' => 'video',
            'order_index' => 1,
            'is_preview' => false,
        ]);
    }

    // ============================================================
    // 1. CERTIFICATE TEMPLATE MODEL
    // ============================================================

    public function test_creates_certificate_template_and_links_to_certificate(): void
    {
        $template = CertificateTemplate::create([
            'name' => 'قالب الشهادة الذهبية الهندسية Premium',
            'design_config' => [
                'border_style' => 'double_gold',
                'seal_color' => '#D4AF37',
                'font_primary' => 'Times New Roman',
                'background' => 'premium_navy',
            ],
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('certificate_templates', ['name' => 'قالب الشهادة الذهبية الهندسية Premium', 'status' => 'active']);

        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => \App\Modules\Course\Models\Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $cert = Certificate::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'enrollment_id' => $enrollment->id,
            'template_id' => $template->id,
            'student_name_snapshot' => $this->student->name,
            'course_title_snapshot_ar' => $this->course->title_ar,
            'course_title_snapshot_en' => $this->course->title_en ?? $this->course->title_ar,
            'instructor_name_snapshot' => $this->instructor->name,
            'grade_percentage' => 92.50,
        ]);

        $this->assertNotNull($cert->template_id);
        $this->assertEquals($template->id, $cert->template_id);
        $this->assertEquals($template->name, $cert->template->name);
        $this->assertIsArray($cert->template->design_config);
        $this->assertEquals('double_gold', $cert->template->design_config['border_style']);
    }

    public function test_certificate_template_scope_active_filters_correctly(): void
    {
        CertificateTemplate::create(['name' => 'نشط', 'design_config' => null, 'status' => 'active']);
        CertificateTemplate::create(['name' => 'موقوف', 'design_config' => null, 'status' => 'inactive']);

        $active = CertificateTemplate::active()->get();
        $this->assertCount(1, $active);
        $this->assertEquals('نشط', $active->first()->name);
    }

    // ============================================================
    // 2. CERTIFICATE PDF SERVICE
    // ============================================================

    public function test_generates_pdf_binary_content_for_certificate(): void
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => \App\Modules\Course\Models\Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $cert = Certificate::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'enrollment_id' => $enrollment->id,
            'student_name_snapshot' => 'Mohamed Al-Hamdan',
            'course_title_snapshot_ar' => 'دورة نمذجة المباني',
            'course_title_snapshot_en' => 'BIM Modeling Course',
            'instructor_name_snapshot' => 'Eng. Faisal Al-Dosari',
            'grade_percentage' => 88.00,
        ]);

        $service = new CertificatePdfService();
        $pdf = $service->generate($cert);

        $this->assertNotEmpty($pdf);
        $this->assertStringStartsWith('%PDF-', $pdf);
        $this->assertStringContainsString('endobj', $pdf);
        $this->assertStringContainsString('%%EOF', $pdf);
    }

    public function test_pdf_download_returns_http_response_with_correct_headers(): void
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => \App\Modules\Course\Models\Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $cert = Certificate::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'enrollment_id' => $enrollment->id,
            'student_name_snapshot' => 'Ali Engineer',
            'course_title_snapshot_ar' => 'BIM Course',
            'course_title_snapshot_en' => 'BIM Course',
            'instructor_name_snapshot' => 'Prof. Instructor',
            'grade_percentage' => 95.00,
        ]);

        $service = new CertificatePdfService();
        $response = $service->download($cert);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));
    }

    // ============================================================
    // 3. ASSIGNMENT RUBRIC MODEL & GRADING
    // ============================================================

    public function test_creates_assignment_rubrics_and_links_to_assignment(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title_ar' => 'مشروع نمذجة Revit — LOD 350',
            'title_en' => 'Revit Modeling Project LOD 350',
            'instructions_ar' => 'قم بإنشاء نموذج بناء متكامل بمستوى LOD 350 مع توثيق الغرف والمساحات.',
            'total_points' => 100.00,
            'max_file_size_mb' => 200,
        ]);

        AssignmentRubric::create(['assignment_id' => $assignment->id, 'criteria' => 'دقة النمذجة الهيكلية BIM LOD 350', 'weight' => 1.50, 'max_score' => 40.00]);
        AssignmentRubric::create(['assignment_id' => $assignment->id, 'criteria' => 'جودة توثيق الأبعاد والقطاعات', 'weight' => 1.00, 'max_score' => 30.00]);
        AssignmentRubric::create(['assignment_id' => $assignment->id, 'criteria' => 'اكتمال بيانات العناصر وخصائص IFC', 'weight' => 1.00, 'max_score' => 30.00]);

        $this->assertDatabaseHas('assignment_rubrics', ['assignment_id' => $assignment->id, 'criteria' => 'دقة النمذجة الهيكلية BIM LOD 350']);
        $this->assertCount(3, $assignment->rubrics);
        $this->assertEquals(100.00, $assignment->rubrics->sum('max_score'));
    }

    public function test_assignment_service_grades_with_rubrics_and_computes_total(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title_ar' => 'مشروع Navisworks لاكتشاف التعارضات',
            'instructions_ar' => 'قم بإعداد تقرير اكتشاف التعارضات الكامل باستخدام Navisworks Manage.',
            'total_points' => 100.00,
            'max_file_size_mb' => 100,
        ]);

        $r1 = AssignmentRubric::create(['assignment_id' => $assignment->id, 'criteria' => 'دقة Clash Detection', 'weight' => 1.00, 'max_score' => 50.00]);
        $r2 = AssignmentRubric::create(['assignment_id' => $assignment->id, 'criteria' => 'تقرير التعارضات', 'weight' => 1.00, 'max_score' => 50.00]);

        Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => \App\Modules\Course\Models\Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $this->student->id,
            'file_path' => 'submissions/clash-report.nwd',
            'file_name' => 'clash-report.nwd',
            'file_size_bytes' => 5_000_000,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        $service = new AssignmentService();
        $graded = $service->gradeSubmissionWithRubrics(
            $submission,
            $this->instructor,
            [
                $r1->id => ['score' => 45.00, 'comment' => 'ممتاز في اكتشاف التعارضات الميكانيكية'],
                $r2->id => ['score' => 42.00, 'comment' => 'تقرير احترافي مع توثيق جيد'],
            ],
            'عمل هندسي احترافي بامتياز'
        );

        $this->assertEquals('GRADED', $graded->status);
        $this->assertEquals(87.00, (float) $graded->grade);
        $this->assertStringContainsString('دقة Clash Detection', $graded->instructor_feedback);
        $this->assertStringContainsString('45 / 50', $graded->instructor_feedback);
    }

    // ============================================================
    // 4. QUESTION BANK ENHANCEMENT
    // ============================================================

    public function test_assessment_question_stores_and_queries_category_difficulty_tags(): void
    {
        $assessment = Assessment::create([
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title_ar' => 'اختبار بنك الأسئلة BIM',
            'type' => 'LESSON_QUIZ',
            'time_limit_minutes' => 20,
            'passing_score_percentage' => 60.00,
            'max_attempts' => 3,
        ]);

        $question = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_text_ar' => 'ما هو اختصار LOD في سياق BIM؟',
            'question_type' => 'SINGLE_CHOICE',
            'category' => 'BIM Fundamentals',
            'difficulty_level' => 'easy',
            'tags' => ['BIM', 'LOD', 'Revit'],
            'points' => 5.00,
            'options' => [
                ['id' => '1', 'text_ar' => 'Level of Detail', 'is_correct' => true],
                ['id' => '2', 'text_ar' => 'Limit of Design', 'is_correct' => false],
            ],
        ]);

        $this->assertNotNull($question->id);
        $this->assertEquals('BIM Fundamentals', $question->category);
        $this->assertEquals('easy', $question->difficulty_level);
        $this->assertContains('LOD', $question->tags);

        $byCategory = AssessmentQuestion::category('BIM Fundamentals')->get();
        $this->assertCount(1, $byCategory);

        $byDifficulty = AssessmentQuestion::difficulty('easy')->get();
        $this->assertCount(1, $byDifficulty);

        $byTag = AssessmentQuestion::tagged('Revit')->get();
        $this->assertCount(1, $byTag);
    }

    // ============================================================
    // 5. LIVE CLASS PROVIDER ABSTRACTION
    // ============================================================

    public function test_zoom_provider_creates_meeting_with_required_fields(): void
    {
        $provider = new ZoomMeetingProvider();
        $result = $provider->createMeeting([
            'topic' => 'Beforbim - Revit Advanced Workshop',
            'start_time' => now()->addDay()->toIso8601String(),
            'duration_minutes' => 90,
        ]);

        $this->assertArrayHasKey('meeting_id', $result);
        $this->assertArrayHasKey('join_url', $result);
        $this->assertNotEmpty($result['meeting_id']);
        $this->assertStringContainsString('zoom.us', $result['join_url']);
    }

    public function test_google_meet_provider_creates_meeting_with_valid_code(): void
    {
        $provider = new GoogleMeetProvider();
        $result = $provider->createMeeting([
            'topic' => 'BIM Coordination Live',
            'start_time' => now()->addDay()->toIso8601String(),
            'duration_minutes' => 60,
        ]);

        $this->assertArrayHasKey('meeting_id', $result);
        $this->assertArrayHasKey('join_url', $result);
        $this->assertStringContainsString('meet.google.com', $result['join_url']);
        $this->assertMatchesRegularExpression('/^[a-z0-9]+-[a-z0-9]+-[a-z0-9]+$/', $result['meeting_id']);
    }

    public function test_live_class_provider_manager_resolves_correct_drivers(): void
    {
        $manager = new LiveClassProviderManager();

        $zoom = $manager->driver('ZOOM');
        $this->assertInstanceOf(ZoomMeetingProvider::class, $zoom);

        $meet = $manager->driver('GOOGLE_MEET');
        $this->assertInstanceOf(GoogleMeetProvider::class, $meet);
    }

    public function test_live_class_provider_manager_throws_for_unknown_driver(): void
    {
        $manager = new LiveClassProviderManager();

        $this->expectException(\InvalidArgumentException::class);
        $manager->driver('MICROSOFT_TEAMS');
    }

    // ============================================================
    // 6. LIVE CLASS SCHEDULING SERVICE
    // ============================================================

    public function test_instructor_can_schedule_live_class_for_their_course(): void
    {
        $service = app(LiveClassService::class);

        $liveClass = $service->scheduleLiveClass($this->course, $this->instructor, [
            'title_ar' => 'الحصة المباشرة الأولى — مقدمة في Revit MEP',
            'title_en' => 'Live Session 1: Revit MEP Introduction',
            'description_ar' => 'حصة تفاعلية مباشرة لتعلم أساسيات نمذجة الأنظمة الميكانيكية.',
            'provider' => 'ZOOM',
            'scheduled_start_time' => now()->addDays(3)->toDateTimeString(),
            'duration_minutes' => 90,
        ]);

        $this->assertInstanceOf(LiveClass::class, $liveClass);
        $this->assertEquals('SCHEDULED', $liveClass->status);
        $this->assertEquals('ZOOM', $liveClass->provider);
        $this->assertEquals($this->course->id, $liveClass->course_id);
        $this->assertNotEmpty($liveClass->meeting_id);
        $this->assertNotEmpty($liveClass->join_url_student);
        $this->assertDatabaseHas('live_classes', ['course_id' => $this->course->id, 'status' => 'SCHEDULED']);
    }

    public function test_enrolled_student_can_join_live_class(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => \App\Modules\Course\Models\Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title_ar' => 'حصة Navisworks للتنسيق',
            'provider' => 'ZOOM',
            'meeting_id' => '82234567890',
            'join_url_student' => 'https://zoom.us/j/82234567890',
            'scheduled_start_time' => now()->addHour(),
            'duration_minutes' => 60,
            'status' => 'SCHEDULED',
        ]);

        $service = app(LiveClassService::class);
        $joinUrl = $service->authorizeStudentJoin($liveClass, $this->student);

        $this->assertEquals($liveClass->join_url_student, $joinUrl);
        $this->assertDatabaseHas('live_class_attendances', [
            'live_class_id' => $liveClass->id,
            'user_id' => $this->student->id,
        ]);
    }

    public function test_unenrolled_student_cannot_join_live_class(): void
    {
        $liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title_ar' => 'حصة مقيدة',
            'provider' => 'GOOGLE_MEET',
            'meeting_id' => 'abc-defg-hij',
            'join_url_student' => 'https://meet.google.com/abc-defg-hij',
            'scheduled_start_time' => now()->addHour(),
            'duration_minutes' => 45,
            'status' => 'SCHEDULED',
        ]);

        $service = app(LiveClassService::class);

        $this->expectException(\InvalidArgumentException::class);
        $service->authorizeStudentJoin($liveClass, $this->student);
    }

    public function test_service_records_student_leave_and_attendance_minutes(): void
    {
        $liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title_ar' => 'حصة قياس الحضور',
            'provider' => 'ZOOM',
            'meeting_id' => '12345678901',
            'join_url_student' => 'https://zoom.us/j/12345678901',
            'scheduled_start_time' => now()->subHour(),
            'duration_minutes' => 60,
            'status' => 'LIVE',
        ]);

        LiveClassAttendance::create([
            'live_class_id' => $liveClass->id,
            'user_id' => $this->student->id,
            'joined_at' => now()->subMinutes(45),
            'attended_minutes' => 0,
        ]);

        $service = app(LiveClassService::class);
        $minutes = $service->recordStudentLeave($liveClass, $this->student);

        $this->assertGreaterThan(0, $minutes);

        $attendance = LiveClassAttendance::where('live_class_id', $liveClass->id)
            ->where('user_id', $this->student->id)->first();
        $this->assertNotNull($attendance->left_at);
        $this->assertGreaterThan(0, $attendance->attended_minutes);
    }

    public function test_instructor_can_complete_live_class_with_recording(): void
    {
        $liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title_ar' => 'حصة BIM للإغلاق',
            'provider' => 'ZOOM',
            'meeting_id' => '99988877766',
            'join_url_student' => 'https://zoom.us/j/99988877766',
            'scheduled_start_time' => now()->subHours(2),
            'duration_minutes' => 90,
            'status' => 'LIVE',
        ]);

        $service = app(LiveClassService::class);
        $recordingUrl = 'https://zoom.us/rec/share/BIM-Coordination-Recording.mp4';
        $completed = $service->completeLiveClass($liveClass, $this->instructor, $recordingUrl);

        $this->assertEquals('COMPLETED', $completed->status);
        $this->assertEquals($recordingUrl, $completed->recording_url);
        $this->assertDatabaseHas('live_classes', ['id' => $liveClass->id, 'status' => 'COMPLETED']);
    }

    public function test_live_class_attendance_model_has_correct_relations(): void
    {
        $liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructor->id,
            'title_ar' => 'حصة اختبار العلاقات',
            'provider' => 'ZOOM',
            'meeting_id' => '11122233344',
            'join_url_student' => 'https://zoom.us/j/11122233344',
            'scheduled_start_time' => now()->addDay(),
            'duration_minutes' => 60,
            'status' => 'SCHEDULED',
        ]);

        $attendance = LiveClassAttendance::create([
            'live_class_id' => $liveClass->id,
            'user_id' => $this->student->id,
            'joined_at' => now(),
            'attended_minutes' => 0,
        ]);

        $this->assertEquals($liveClass->id, $attendance->liveClass->id);
        $this->assertEquals($this->student->id, $attendance->user->id);
        $this->assertCount(1, $liveClass->attendees);
    }
}
