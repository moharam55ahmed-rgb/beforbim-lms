<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentQuestion;
use App\Modules\Assessment\Services\AssessmentService;
use App\Modules\Assignment\Models\Assignment;
use App\Modules\Assignment\Services\AssignmentService;
use App\Modules\Auth\Services\InstructorDashboardService;
use App\Modules\Auth\Services\StudentDashboardService;
use App\Modules\Category\Models\Category;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Certificate\Services\CertificateService;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseCompletionService;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Services\EnrollmentService;
use App\Modules\ExamSecurity\Services\ExamSecurityService;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Notification\Services\NotificationService;
use App\Modules\Progress\Services\LessonProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentAndCertificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected User $instructor;

    protected User $admin;

    protected Course $course;

    protected CourseSection $section;

    protected Lesson $lesson;

    protected Assessment $quiz;

    protected Assignment $assignment;

    protected AssessmentQuestion $question1;

    protected AssessmentQuestion $question2;

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
            'name_ar' => 'هندسة مدنية وإنشائية',
            'name_en' => 'Civil Engineering',
            'slug' => 'civil-eng-'.uniqid(),
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دبلومة التصميم الإنشائي ونمذجة الـ BIM',
            'title_en' => 'Structural Design Diploma',
            'slug' => 'structural-diploma-'.uniqid(),
            'price' => 1500.00,
            'currency' => 'SAR',
            'status' => 'APPROVED',
            'published_at' => now(),
            'preview_enabled' => true,
        ]);

        $this->section = CourseSection::create([
            'course_id' => $this->course->id,
            'title_ar' => 'الوحدة الأولى',
            'order_index' => 1,
        ]);

        $this->lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title_ar' => 'مقدمة في الكود الإنشائي',
            'lesson_type' => 'video',
            'order_index' => 1,
            'is_preview' => false,
        ]);

        // Assessment setup
        $this->quiz = Assessment::create([
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title_ar' => 'اختبار المفاهيم الإنشائية الأساسية',
            'type' => 'LESSON_QUIZ',
            'time_limit_minutes' => 30,
            'passing_score_percentage' => 70.00,
            'max_attempts' => 2,
            'is_proctored_mode' => true,
            'max_violations_allowed' => 2,
        ]);

        $this->question1 = AssessmentQuestion::create([
            'assessment_id' => $this->quiz->id,
            'question_text_ar' => 'ما هو المعيار المعتمد لتبادل نماذج الـ BIM مفتوحة المصدر؟',
            'question_type' => 'SINGLE_CHOICE',
            'points' => 50.00,
            'order_index' => 1,
            'options' => [
                ['id' => '1', 'text_ar' => 'صيغة DWG', 'is_correct' => false],
                ['id' => '2', 'text_ar' => 'صيغة IFC', 'is_correct' => true],
                ['id' => '3', 'text_ar' => 'صيغة PDF', 'is_correct' => false],
            ],
        ]);

        $this->question2 = AssessmentQuestion::create([
            'assessment_id' => $this->quiz->id,
            'question_text_ar' => 'أي من البرامج التالية يُستخدم في اكتشاف التعارضات الهندسية؟',
            'question_type' => 'SINGLE_CHOICE',
            'points' => 50.00,
            'order_index' => 2,
            'options' => [
                ['id' => '1', 'text_ar' => 'Autodesk Navisworks', 'is_correct' => true],
                ['id' => '2', 'text_ar' => 'Adobe Photoshop', 'is_correct' => false],
            ],
        ]);

        // Assignment setup
        $this->assignment = Assignment::create([
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title_ar' => 'مشروع نمذجة برج خرساني متكامل',
            'instructions_ar' => 'يرجى تقديم ملف النموذج بصيغة RVT مع إخراج جداول الكميات.',
            'total_points' => 100.00,
            'due_date' => now()->addDays(7),
        ]);
    }

    public function test_student_dashboard_data_integration(): void
    {
        $enrollmentService = app(EnrollmentService::class);
        $enrollment = $enrollmentService->grantManualEnrollment($this->student, $this->course, $this->admin, 'Testing dashboard');

        $dashboardService = app(StudentDashboardService::class);
        $data = $dashboardService->getDashboardData($this->student);

        $this->assertNotEmpty($data['enrolled_courses']);
        $this->assertEquals(1, $data['progress_stats']['total_enrolled']);
        $this->assertNotNull($data['continue_learning']);
        $this->assertEquals($this->course->id, $data['continue_learning']['course']->id);
        $this->assertNotEmpty($data['pending_assignments']);
        $this->assertNotEmpty($data['upcoming_assessments']);
        $this->assertEmpty($data['certificates']);
    }

    public function test_instructor_analytics_foundation(): void
    {
        $enrollmentService = app(EnrollmentService::class);
        $enrollmentService->grantManualEnrollment($this->student, $this->course, $this->admin, 'Testing analytics');

        $instructorService = app(InstructorDashboardService::class);
        $metrics = $instructorService->getMetrics($this->instructor);

        $this->assertEquals(1, $metrics['total_courses']);
        $this->assertEquals(1, $metrics['published_courses']);
        $this->assertEquals(1, $metrics['students_count']);
        $this->assertArrayHasKey('completion_rate', $metrics);
        $this->assertArrayHasKey('average_rating', $metrics);
        $this->assertArrayHasKey('revenue_summary', $metrics);
        $this->assertArrayHasKey('questions_count', $metrics);
    }

    public function test_assessment_start_and_automated_grading(): void
    {
        app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Testing quiz');

        $assessmentService = app(AssessmentService::class);

        // 1. Start attempt
        $attempt = $assessmentService->startAttempt($this->student, $this->quiz);
        $this->assertEquals('IN_PROGRESS', $attempt->status);
        $this->assertEquals(1, $attempt->attempt_number);

        // 2. Submit correct answers: Q1 -> '2', Q2 -> '1'
        $gradedAttempt = $assessmentService->submitAttempt($attempt, [
            $this->question1->id => '2',
            $this->question2->id => '1',
        ]);

        $this->assertEquals('GRADED', $gradedAttempt->status);
        $this->assertEquals(100.00, (float) $gradedAttempt->score_percentage);
        $this->assertTrue($gradedAttempt->passed);
    }

    public function test_exam_security_anti_cheat_disqualification(): void
    {
        app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Testing anti-cheat');

        $assessmentService = app(AssessmentService::class);
        $securityService = app(ExamSecurityService::class);

        $attempt = $assessmentService->startAttempt($this->student, $this->quiz);

        // Violation 1 (Max is 2)
        $securityService->logViolation($attempt, 'TAB_BLUR', '127.0.0.1', 'Switched tabs');
        $this->assertEquals(1, $attempt->fresh()->anti_cheat_violations_count);
        $this->assertEquals('IN_PROGRESS', $attempt->fresh()->status);

        // Violation 2 -> Triggers automatic DISQUALIFICATION
        $securityService->logViolation($attempt, 'FULLSCREEN_EXIT', '127.0.0.1', 'Exited fullscreen');
        $this->assertEquals(2, $attempt->fresh()->anti_cheat_violations_count);
        $this->assertEquals('DISQUALIFIED', $attempt->fresh()->status);
        $this->assertFalse($attempt->fresh()->passed);
    }

    public function test_assignment_submission_and_instructor_grading(): void
    {
        app(EnrollmentService::class)->grantManualEnrollment($this->student, $this->course, $this->admin, 'Testing assignment');

        $assignmentService = app(AssignmentService::class);

        // 1. Student submits work
        $submission = $assignmentService->submitAssignment(
            $this->student,
            $this->assignment,
            'تم تصميم جميع الأعمدة والكمرات وفق الكود السعودي',
            'assignments/model.rvt',
            'model.rvt',
            1048576
        );

        $this->assertEquals('SUBMITTED', $submission->status);
        $this->assertNull($submission->grade);

        // 2. Instructor grades submission
        $gradedSubmission = $assignmentService->gradeSubmission(
            $submission,
            $this->instructor,
            95.00,
            'عمل هندسي ممتاز ونموذج متقن، تم مراعاة اشتراطات العزل والتسليح.'
        );

        $this->assertEquals('GRADED', $gradedSubmission->status);
        $this->assertEquals(95.00, (float) $gradedSubmission->grade);
        $this->assertEquals($this->instructor->id, $gradedSubmission->graded_by_user_id);
    }

    public function test_course_completion_service_and_certificate_issuance(): void
    {
        $enrollmentService = app(EnrollmentService::class);
        $enrollment = $enrollmentService->grantManualEnrollment($this->student, $this->course, $this->admin, 'Testing completion');

        $completionService = app(CourseCompletionService::class);

        // 1. Initial check: uncompleted
        $evalBefore = $completionService->evaluateCriteria($this->student, $this->course);
        $this->assertFalse($evalBefore['is_eligible']);

        // 2. Complete lesson
        app(LessonProgressService::class)->toggleCompletion($this->student, $this->lesson);

        // 3. Pass quiz
        $attempt = app(AssessmentService::class)->startAttempt($this->student, $this->quiz);
        app(AssessmentService::class)->submitAttempt($attempt, [
            $this->question1->id => '2',
            $this->question2->id => '1',
        ]);

        // 4. Submit and pass assignment
        $submission = app(AssignmentService::class)->submitAssignment(
            $this->student,
            $this->assignment,
            'Notes',
            'path.rvt',
            'path.rvt',
            1000
        );
        app(AssignmentService::class)->gradeSubmission($submission, $this->instructor, 90.00);

        // 5. Evaluate criteria again -> now fully eligible!
        $evalAfter = $completionService->evaluateCriteria($this->student, $this->course);
        $this->assertTrue($evalAfter['is_eligible']);

        // 6. Process completion and issue certificate
        $result = $completionService->processCompletion($this->student, $this->course);
        $this->assertTrue($result['is_eligible']);
        $this->assertNotNull($result['certificate']);
        $this->assertInstanceOf(Certificate::class, $result['certificate']);
        $this->assertEquals('active', $result['certificate']->status);

        // 7. Public verification of certificate
        $certificateService = app(CertificateService::class);
        $verifiedCert = $certificateService->verifyCertificate($result['certificate']->verification_code);
        $this->assertNotNull($verifiedCert);
        $this->assertEquals($this->student->id, $verifiedCert->user_id);
    }

    public function test_notification_service_integration(): void
    {
        $notificationService = app(NotificationService::class);

        // Test sending general notification
        $notificationService->send(
            user: $this->student,
            title: 'إشعار تجريبي',
            message: 'محتوى الإشعار التجريبي لمنصة Beforbim',
            channels: ['database']
        );

        $this->assertEquals(1, $this->student->notifications()->count());
        $notification = $this->student->notifications()->first();
        $this->assertEquals('إشعار تجريبي', $notification->data['title']);

        // Test mark as read
        $this->assertTrue($notificationService->markAsRead($this->student, $notification->id));
        $this->assertEquals(0, $this->student->unreadNotifications()->count());
    }
}
