<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\AuditLog\Services\AuditQueryService;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\SupportTicket\Services\SupportTicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuditLogAndSupportTicketTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instructor;

    protected User $student;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name_ar' => 'مسؤول', 'display_name_en' => 'Admin']);
        $instructorRole = Role::firstOrCreate(['name' => 'instructor'], ['display_name_ar' => 'مدرب', 'display_name_en' => 'Instructor']);
        $studentRole = Role::firstOrCreate(['name' => 'student'], ['display_name_ar' => 'طالب', 'display_name_en' => 'Student']);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->instructor = User::factory()->create(['status' => 'active']);
        $this->instructor->roles()->sync([$instructorRole->id]);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->sync([$studentRole->id]);

        $category = Category::create([
            'name_ar' => 'هندسة معمارية BIM',
            'name_en' => 'Architectural BIM',
            'slug' => 'architectural-bim',
        ]);

        $this->course = Course::create([
            'uuid' => (string) Str::uuid(),
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دورة النمذجة المعمارية المتقدمة عبر Revit',
            'title_en' => 'Advanced Architectural Modeling via Revit',
            'slug' => 'advanced-revit-architectural-modeling',
            'short_description_ar' => 'وصف مختصر',
            'description_ar' => 'وصف كامل',
            'price' => 350.00,
            'status' => 'APPROVED',
            'published_at' => now(),
        ]);
    }

    public function test_audit_log_records_immutable_events(): void
    {
        $log = AuditLog::log(
            module: 'Security',
            action: 'DEVICE_SESSION_TERMINATED',
            actor: $this->admin,
            target: $this->student,
            oldValues: ['active_session_id' => 'sess-123'],
            newValues: ['active_session_id' => null],
            reason: 'Administrative remote logout initiated'
        );

        $this->assertDatabaseHas('audit_logs', [
            'id' => $log->id,
            'module' => 'Security',
            'action' => 'DEVICE_SESSION_TERMINATED',
            'actor_id' => $this->admin->id,
        ]);

        $queryService = app(AuditQueryService::class);
        $logs = $queryService->getFilteredLogs(['module' => 'Security']);

        $this->assertCount(1, $logs);
        $this->assertEquals('DEVICE_SESSION_TERMINATED', $logs->first()->action);
    }

    public function test_admin_can_view_audit_logs_and_show_detail(): void
    {
        $log = AuditLog::log(
            module: 'Payment',
            action: 'PAYMENT_VERIFIED',
            actor: $this->admin,
            reason: 'Manual bank transfer verified'
        );

        $response = $this->actingAs($this->admin)->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('PAYMENT_VERIFIED');

        $showResponse = $this->actingAs($this->admin)->get(route('admin.audit-logs.show', $log));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($this->admin->name);
    }

    public function test_student_can_create_support_ticket_with_attachment(): void
    {
        $fakeFile = UploadedFile::fake()->create('error_screenshot.png', 500, 'image/png');

        $response = $this->actingAs($this->student)->post(route('support.store'), [
            'subject' => 'مشكلة في تحميل ملف مشروع Revit',
            'category' => 'TECHNICAL',
            'priority' => 'HIGH',
            'course_id' => $this->course->id,
            'message' => 'أواجه مشكلة في تحميل الملف المرفق مع درس الأوتوديسك ريفيت، تظهر لي رسالة خطأ.',
            'attachment' => $fakeFile,
        ]);

        $response->assertRedirect();

        $ticket = SupportTicket::where('user_id', $this->student->id)->first();
        $this->assertNotNull($ticket);
        $this->assertStringStartsWith('TCK-', $ticket->ticket_number);
        $this->assertEquals('OPEN', $ticket->status);
        $this->assertCount(1, $ticket->messages);

        $message = $ticket->messages->first();
        $this->assertCount(1, $message->attachments);
        $this->assertEquals('error_screenshot.png', $message->attachments->first()->file_name);
    }

    public function test_student_and_staff_ticket_conversation_flow(): void
    {
        $service = app(SupportTicketService::class);

        $ticket = $service->createTicket(
            student: $this->student,
            data: [
                'subject' => 'استفسار عن تسليم المشروع العملي',
                'category' => 'ACADEMIC_CONTENT',
                'message' => 'متى الموعد النهائي لتسليم المرحلة الثانية من فيلا الـ Revit؟',
            ]
        );

        // Staff (Admin/Instructor) replies publicly
        $service->addReply(
            user: $this->instructor,
            ticket: $ticket,
            messageText: 'الموعد النهائي هو يوم الخميس القادم الساعة 11 مساءً بتوقيت مكة المكرمة.',
            isInternalNote: false
        );

        $ticket->refresh();
        $this->assertEquals('WAITING_FOR_STUDENT', $ticket->status);

        // Staff adds internal note (not visible to student)
        $service->addReply(
            user: $this->instructor,
            ticket: $ticket,
            messageText: 'ملاحظة داخلية: الطالب يحتاج تمديد يومين إذا واجه مشاكل في الرندر.',
            isInternalNote: true
        );

        // Student views ticket: internal note should not be visible
        $studentView = $this->actingAs($this->student)->get(route('support.show', $ticket));
        $studentView->assertStatus(200);
        $studentView->assertSee('الموعد النهائي هو يوم الخميس القادم');
        $studentView->assertDontSee('ملاحظة داخلية: الطالب يحتاج تمديد');

        // Admin views ticket: internal note should be visible
        $adminView = $this->actingAs($this->admin)->get(route('admin.support.show', $ticket));
        $adminView->assertStatus(200);
        $adminView->assertSee('ملاحظة داخلية: الطالب يحتاج تمديد');
    }

    public function test_admin_can_reassign_and_resolve_ticket(): void
    {
        $service = app(SupportTicketService::class);

        $ticket = $service->createTicket(
            student: $this->student,
            data: [
                'subject' => 'طلب مساعدة فنية',
                'category' => 'TECHNICAL',
                'message' => 'مشكلة في تشغيل الفيديو التفاعلي.',
            ]
        );

        // Assign to instructor
        $assignResponse = $this->actingAs($this->admin)->post(route('admin.support.assign', $ticket), [
            'assigned_to_user_id' => $this->instructor->id,
        ]);
        $assignResponse->assertRedirect();

        $ticket->refresh();
        $this->assertEquals($this->instructor->id, $ticket->assigned_to_user_id);
        $this->assertEquals('IN_PROGRESS', $ticket->status);

        // Resolve ticket
        $statusResponse = $this->actingAs($this->admin)->post(route('admin.support.status', $ticket), [
            'status' => 'RESOLVED',
        ]);
        $statusResponse->assertRedirect();

        $ticket->refresh();
        $this->assertEquals('RESOLVED', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);
    }
}
