<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Cart\Models\Coupon;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\Course\Services\WishlistService;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Enrollment\Services\EnrollmentService;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonResource;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Events\PaymentRefunded;
use App\Modules\Payment\Gateways\CardPaymentGateway;
use App\Modules\Payment\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommerceAndAccessRefinementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected User $instructor;

    protected User $admin;

    protected Course $courseA;

    protected Course $courseB;

    protected Category $category;

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

        $this->category = Category::create([
            'name_ar' => 'هندسة مدنية',
            'name_en' => 'Civil Engineering',
            'slug' => 'civil-eng-'.uniqid(),
            'is_active' => true,
        ]);

        $this->courseA = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'تصميم المنشآت الخرسانية',
            'title_en' => 'Concrete Design',
            'price' => 500.00,
            'currency' => 'SAR',
            'status' => 'APPROVED',
            'published_at' => now(),
            'preview_enabled' => true,
        ]);

        $this->courseB = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title_ar' => 'نمذجة معلومات البناء BIM',
            'title_en' => 'BIM Architecture',
            'price' => 700.00,
            'currency' => 'SAR',
            'status' => 'APPROVED',
            'published_at' => now(),
            'preview_enabled' => true,
        ]);
    }

    public function test_payment_gateway_abstraction_and_event_dispatching(): void
    {
        Event::fake([PaymentCompleted::class]);

        $order = Order::create([
            'user_id' => $this->student->id,
            'order_number' => 'ORD-'.strtoupper(uniqid()),
            'subtotal' => 500.00,
            'discount_amount' => 0.00,
            'tax_amount' => 0.00,
            'total_amount' => 500.00,
            'currency' => 'SAR',
            'status' => 'PENDING',
        ]);

        $order->items()->create([
            'purchasable_type' => Course::class,
            'purchasable_id' => $this->courseA->id,
            'course_id' => $this->courseA->id,
            'title_snapshot' => $this->courseA->title_ar,
            'unit_price' => 500.00,
            'quantity' => 1,
            'total_price' => 500.00,
        ]);

        $paymentService = app(PaymentService::class);
        $gateway = $paymentService->gateway('card');
        $this->assertInstanceOf(CardPaymentGateway::class, $gateway);

        $payment = $paymentService->processOrderPayment($order, 'CARD', 'card', [
            'card_last4' => '1234',
        ]);

        $this->assertEquals('COMPLETED', $payment->status);
        $this->assertEquals('COMPLETED', $order->fresh()->status);
        $this->assertDatabaseHas('transactions', [
            'payment_id' => $payment->id,
            'transaction_type' => 'CHARGE',
            'amount' => 500.00,
        ]);

        // Enrollment activation strictly for purchased course
        $this->assertTrue($this->student->isEnrolledIn($this->courseA));
        $this->assertFalse($this->student->isEnrolledIn($this->courseB));

        Event::assertDispatched(PaymentCompleted::class);
    }

    public function test_payment_refund_and_enrollment_revocation(): void
    {
        Event::fake([PaymentRefunded::class]);

        $order = Order::create([
            'user_id' => $this->student->id,
            'order_number' => 'ORD-'.strtoupper(uniqid()),
            'subtotal' => 500.00,
            'discount_amount' => 0.00,
            'tax_amount' => 0.00,
            'total_amount' => 500.00,
            'currency' => 'SAR',
            'status' => 'PENDING',
        ]);

        $order->items()->create([
            'purchasable_type' => Course::class,
            'purchasable_id' => $this->courseA->id,
            'course_id' => $this->courseA->id,
            'title_snapshot' => $this->courseA->title_ar,
            'unit_price' => 500.00,
            'quantity' => 1,
            'total_price' => 500.00,
        ]);

        $paymentService = app(PaymentService::class);
        $payment = $paymentService->processOrderPayment($order, 'CARD', 'card');

        $this->assertTrue($this->student->isEnrolledIn($this->courseA));

        // Process refund
        $paymentService->refundPayment($payment, 500.00, 'Student requested refund', $this->admin);

        $this->assertEquals('REFUNDED', $payment->fresh()->status);
        $this->assertEquals('REFUNDED', $order->fresh()->status);

        // Verify transaction record
        $this->assertDatabaseHas('transactions', [
            'payment_id' => $payment->id,
            'transaction_type' => 'REFUND',
            'amount' => -500.00,
        ]);

        // Enrollment must be revoked
        $enrollment = Enrollment::where('user_id', $this->student->id)->where('course_id', $this->courseA->id)->first();
        $this->assertEquals('REVOKED', $enrollment->status);
        $this->assertFalse($this->student->isEnrolledIn($this->courseA));

        // Status history must track revocation
        $this->assertDatabaseHas('enrollment_status_history', [
            'enrollment_id' => $enrollment->id,
            'status' => 'REVOKED',
            'changed_by' => $this->admin->id,
        ]);

        Event::assertDispatched(PaymentRefunded::class);
    }

    public function test_coupon_validation_and_discount_calculation(): void
    {
        // 1. Percentage Coupon with Max Discount
        $coupon = Coupon::create([
            'code' => 'ENGINEER20',
            'type' => 'percentage',
            'value' => 20.00,
            'max_discount' => 50.00,
            'status' => 'active',
            'usage_limit' => 10,
            'used_count' => 0,
        ]);

        $this->assertTrue($coupon->isValid());
        // 20% of 500 = 100, but capped at 50
        $this->assertEquals(50.00, $coupon->calculateDiscount(500.00));

        // 2. Course-specific coupon
        $specificCoupon = Coupon::create([
            'code' => 'BIM50',
            'type' => 'fixed',
            'value' => 50.00,
            'status' => 'active',
        ]);
        $specificCoupon->courses()->attach($this->courseB->id);

        $this->assertTrue($specificCoupon->isValid($this->courseB));
        $this->assertFalse($specificCoupon->isValid($this->courseA));
        $this->assertEquals(50.00, $specificCoupon->calculateDiscount(700.00));
    }

    public function test_wishlist_service_rules_and_restrictions(): void
    {
        $wishlistService = app(WishlistService::class);

        // Student can add
        $this->assertTrue($wishlistService->toggle($this->student, $this->courseA));
        $this->assertTrue($wishlistService->has($this->student, $this->courseA));

        // Prevents duplicates (toggle removes when clicked again)
        $this->assertFalse($wishlistService->toggle($this->student, $this->courseA));
        $this->assertFalse($wishlistService->has($this->student, $this->courseA));

        // Re-add to wishlist
        $wishlistService->add($this->student, $this->courseA);
        $this->assertCount(1, $wishlistService->getStudentWishlist($this->student));

        // Admin cannot use student wishlist
        $this->expectException(\InvalidArgumentException::class);
        $wishlistService->add($this->admin, $this->courseA);
    }

    public function test_course_access_service_comprehensive_validation(): void
    {
        $accessService = app(CourseAccessService::class);

        $section = CourseSection::create([
            'course_id' => $this->courseA->id,
            'title_ar' => 'المقدمة الهندسية',
            'title_en' => 'Engineering Intro',
            'order_index' => 1,
        ]);

        $previewLesson = Lesson::create([
            'section_id' => $section->id,
            'title_ar' => 'الدرس التعريفي المجاني',
            'title_en' => 'Free Overview Lesson',
            'lesson_type' => 'video',
            'order_index' => 1,
            'is_preview' => true,
        ]);

        $privateLesson = Lesson::create([
            'section_id' => $section->id,
            'title_ar' => 'التحليل الإنشائي المتقدم',
            'title_en' => 'Advanced Structural Analysis',
            'lesson_type' => 'video',
            'order_index' => 2,
            'is_preview' => false,
        ]);

        $resource = LessonResource::create([
            'lesson_id' => $privateLesson->id,
            'title_ar' => 'مخططات إنشائية CAD',
            'title_en' => 'Structural CAD Drawings',
            'file_name' => 'structural_plans.dwg',
            'file_path' => 'courses/resources/structural_plans.dwg',
            'file_extension' => 'dwg',
            'file_size_bytes' => 10240,
            'mime_type' => 'application/octet-stream',
            'is_downloadable' => true,
        ]);

        // 1. Guest can access preview lesson, but NOT private lesson or private resource
        $this->assertTrue($accessService->canAccessLesson(null, $previewLesson));
        $this->assertFalse($accessService->canAccessLesson(null, $privateLesson));
        $this->assertFalse($accessService->canDownloadResource(null, $resource));

        // 2. Unenrolled student can access preview, but NOT private lesson or resource
        $this->assertTrue($accessService->canAccessLesson($this->student, $previewLesson));
        $this->assertFalse($accessService->canAccessLesson($this->student, $privateLesson));
        $this->assertFalse($accessService->canDownloadResource($this->student, $resource));

        // 3. Course Instructor has full access
        $this->assertTrue($accessService->canAccessLesson($this->instructor, $privateLesson));
        $this->assertTrue($accessService->canDownloadResource($this->instructor, $resource));
        $this->assertTrue($accessService->canManageCourse($this->instructor, $this->courseA));

        // 4. Enroll student in Course A
        $enrollmentService = app(EnrollmentService::class);
        $enrollmentService->grantManualEnrollment($this->student, $this->courseA, $this->admin, 'Testing enrollment');

        $this->assertTrue($accessService->canAccessLesson($this->student, $privateLesson));
        $this->assertTrue($accessService->canDownloadResource($this->student, $resource));

        // But student cannot access Course B (Strict Isolation)
        $sectionB = CourseSection::create([
            'course_id' => $this->courseB->id,
            'title_ar' => 'مدخل BIM',
            'order_index' => 1,
        ]);
        $privateLessonB = Lesson::create([
            'section_id' => $sectionB->id,
            'title_ar' => 'درس BIM مغلق',
            'order_index' => 1,
            'is_preview' => false,
        ]);
        $this->assertFalse($accessService->canAccessLesson($this->student, $privateLessonB));

        // 5. Account restriction: suspended/blocked student is locked out of ALL content
        $this->student->update(['status' => 'suspended']);
        $this->assertFalse($accessService->canAccessLesson($this->student, $previewLesson));
        $this->assertFalse($accessService->canAccessLesson($this->student, $privateLesson));
        $this->assertFalse($accessService->canDownloadResource($this->student, $resource));
    }
}
