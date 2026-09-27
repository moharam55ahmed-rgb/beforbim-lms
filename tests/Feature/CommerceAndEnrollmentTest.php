<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Enrollment\Services\EnrollmentService;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Lesson\Policies\LessonPolicy;
use App\Modules\Notification\Events\EnrollmentApproved;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Models\Payment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use RuntimeException;
use Tests\TestCase;

class CommerceAndEnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    protected User $student;

    protected User $admin;

    protected User $instructor;

    protected Course $courseA;

    protected Course $courseB;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name_ar' => 'مسؤول', 'display_name_en' => 'Admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student'], ['display_name_ar' => 'طالب', 'display_name_en' => 'Student']);
        $instructorRole = Role::firstOrCreate(['name' => 'instructor'], ['display_name_ar' => 'مدرب', 'display_name_en' => 'Instructor']);

        $this->student = User::factory()->create(['status' => 'active']);
        $this->student->roles()->sync([$studentRole->id]);

        $this->admin = User::factory()->create(['status' => 'active']);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->instructor = User::factory()->create(['status' => 'active']);
        $this->instructor->roles()->sync([$instructorRole->id]);

        $category = Category::create([
            'name_ar' => 'تصنيف BIM تجاري',
            'name_en' => 'Commercial BIM',
            'slug' => 'commercial-bim-'.rand(100, 999),
            'is_active' => true,
        ]);

        $this->courseA = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دورة تصميم المنشآت الخرسانية',
            'status' => 'APPROVED',
            'published_at' => now(),
            'price' => 150.00,
            'sale_price' => 120.00,
            'currency' => 'USD',
            'level' => 'INTERMEDIATE',
        ]);

        $this->courseB = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دورة التنسيق وإدارة المشروعات',
            'status' => 'APPROVED',
            'published_at' => now(),
            'price' => 200.00,
            'currency' => 'USD',
            'level' => 'ADVANCED',
        ]);
    }

    public function test_user_can_add_course_to_cart_and_view_totals(): void
    {
        $response = $this->actingAs($this->student)->post(route('cart.add', $this->courseA->id));
        $response->assertRedirect(route('cart.index'));

        // View cart
        $cartView = $this->actingAs($this->student)->get(route('cart.index'));
        $cartView->assertOk();
        $cartView->assertSee('دورة تصميم المنشآت الخرسانية');
        $cartView->assertSee('120'); // effective price

        // Cannot add duplicate course
        $duplicateResponse = $this->actingAs($this->student)->post(route('cart.add', $this->courseA->id));
        $duplicateResponse->assertRedirect(route('cart.index'));
    }

    public function test_cannot_add_already_enrolled_course_to_cart(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->courseA->id,
            'enrollable_type' => Course::class,
            'enrollable_id' => $this->courseA->id,
            'source' => 'DIRECT_PURCHASE',
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($this->student)->post(route('cart.add', $this->courseA->id));
        $response->assertSessionHas('error');
    }

    public function test_payment_verification_is_strictly_mandatory_before_enrollment(): void
    {
        // Add to cart and create order
        $this->actingAs($this->student)->post(route('cart.add', $this->courseA->id));

        $order = Order::create([
            'user_id' => $this->student->id,
            'subtotal' => 120,
            'total_amount' => 120,
            'currency' => 'USD',
            'status' => 'PENDING',
        ]);

        $order->items()->create([
            'purchasable_type' => Course::class,
            'purchasable_id' => $this->courseA->id,
            'course_id' => $this->courseA->id,
            'title_snapshot' => $this->courseA->title_ar,
            'unit_price' => 120,
            'total_price' => 120,
        ]);

        $enrollmentService = app(EnrollmentService::class);

        // Attempting to activate enrollment without payment must throw exception
        $this->expectException(RuntimeException::class);
        $enrollmentService->activateEnrollmentForOrder($order);
    }

    public function test_direct_electronic_payment_activates_enrollment_strictly_for_purchased_course(): void
    {
        Event::fake([EnrollmentApproved::class]);

        // Student buys course A
        $this->actingAs($this->student)->post(route('cart.add', $this->courseA->id));

        $response = $this->actingAs($this->student)->post(route('checkout.process'), [
            'payment_method' => 'CARD',
        ]);

        $order = Order::where('user_id', $this->student->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('COMPLETED', $order->status);

        $response->assertRedirect(route('orders.show', $order->order_number));

        // Verify enrollment in Course A
        $enrollmentService = app(EnrollmentService::class);
        $this->assertTrue($enrollmentService->hasActiveAccess($this->student, $this->courseA));

        // Critical Rule: Buying Course A does NOT unlock Course B!
        $this->assertFalse($enrollmentService->hasActiveAccess($this->student, $this->courseB));

        Event::assertDispatched(EnrollmentApproved::class);
    }

    public function test_bank_transfer_workflow_requires_admin_verification(): void
    {
        Event::fake([EnrollmentApproved::class]);

        $this->actingAs($this->student)->post(route('cart.add', $this->courseA->id));

        $response = $this->actingAs($this->student)->post(route('checkout.process'), [
            'payment_method' => 'BANK_TRANSFER',
            'receipt_url' => 'https://storage.beforbim.com/receipts/bank_123.jpg',
            'notes' => 'تم التحويل من حساب الراجحي',
        ]);

        $order = Order::where('user_id', $this->student->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('PROCESSING', $order->status);

        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertEquals('REQUIRES_ADMIN_VERIFICATION', $payment->status);

        // Student does NOT have course access yet!
        $enrollmentService = app(EnrollmentService::class);
        $this->assertFalse($enrollmentService->hasActiveAccess($this->student, $this->courseA));

        // Admin verifies the receipt
        $adminResponse = $this->actingAs($this->admin)->post(route('admin.payments.verify', $payment->id));
        $adminResponse->assertSessionHas('success');

        $payment->refresh();
        $order->refresh();

        $this->assertEquals('COMPLETED', $payment->status);
        $this->assertEquals('COMPLETED', $order->status);
        $this->assertEquals($this->admin->id, $payment->verified_by_user_id);

        // Now student has access!
        $this->assertTrue($enrollmentService->hasActiveAccess($this->student, $this->courseA));
        Event::assertDispatched(EnrollmentApproved::class);
    }

    public function test_preview_system_authorizes_preview_lesson_and_protects_private_content(): void
    {
        $section = CourseSection::create([
            'course_id' => $this->courseA->id,
            'title_ar' => 'مقدمة الدورة',
            'order_index' => 1,
        ]);

        $previewLesson = Lesson::create([
            'section_id' => $section->id,
            'title_ar' => 'المحاضرة التمهيدية المجانية',
            'lesson_type' => 'VIDEO',
            'is_preview' => true,
            'is_preview_free' => true,
        ]);

        $lockedLesson = Lesson::create([
            'section_id' => $section->id,
            'title_ar' => 'محاضرة التصميم المتقدم المغلقة',
            'lesson_type' => 'VIDEO',
            'is_preview' => false,
            'is_preview_free' => false,
        ]);

        // Unauthenticated guest can preview previewLesson
        $policy = app(LessonPolicy::class);
        $this->assertTrue($policy->view(null, $previewLesson));

        // Guest cannot access lockedLesson
        $this->assertFalse($policy->view(null, $lockedLesson));

        // Guest cannot access private resources of previewLesson
        $this->assertFalse($policy->viewResource(null, $previewLesson));

        // Non-enrolled student cannot access lockedLesson
        $this->assertFalse($policy->view($this->student, $lockedLesson));

        // Enrolled student can access lockedLesson
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->courseA->id,
            'enrollable_type' => Course::class,
            'enrollable_id' => $this->courseA->id,
            'source' => 'DIRECT_PURCHASE',
            'status' => 'ACTIVE',
            'enrolled_at' => now(),
        ]);

        $this->assertTrue($policy->view($this->student, $lockedLesson));
        $this->assertTrue($policy->viewResource($this->student, $previewLesson));
    }
}
