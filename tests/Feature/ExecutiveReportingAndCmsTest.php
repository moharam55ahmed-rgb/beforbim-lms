<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Payment\Models\Payment;
use App\Modules\Report\Services\AcademicAnalyticsService;
use App\Modules\Report\Services\FinancialReportService;
use App\Modules\Setting\Services\CmsSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExecutiveReportingAndCmsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instructor;

    protected User $student;

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

        $category = Category::create([
            'name_ar' => 'إدارة مشاريع BIM',
            'name_en' => 'BIM Management',
            'slug' => 'bim-management',
        ]);

        $this->course = Course::create([
            'uuid' => (string) Str::uuid(),
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title_ar' => 'دورة دبلوم إدارة وتنسيق BIM الاحترافية',
            'title_en' => 'Professional BIM Management Diploma',
            'slug' => 'bim-management-diploma',
            'short_description_ar' => 'وصف مختصر للدورة',
            'description_ar' => 'وصف تفصيلي كامل',
            'price' => 500.00,
            'sale_price' => 400.00,
            'status' => 'APPROVED',
            'published_at' => now(),
        ]);
    }

    public function test_cms_setting_service_handles_get_set_and_types_with_cache(): void
    {
        $service = app(CmsSettingService::class);

        // String setting
        $service->set('site_title', 'Beforbim Platform', 'branding', 'string', true);
        $this->assertEquals('Beforbim Platform', $service->get('site_title'));

        // Integer setting
        $service->set('max_login_attempts', 5, 'security', 'integer', false);
        $this->assertSame(5, $service->get('max_login_attempts'));

        // Boolean setting
        $service->set('enable_watermark', true, 'security', 'boolean', true);
        $this->assertTrue($service->get('enable_watermark'));

        // Public settings lookup
        $public = $service->getPublicSettings();
        $this->assertArrayHasKey('site_title', $public);
        $this->assertArrayHasKey('enable_watermark', $public);
        $this->assertArrayNotHasKey('max_login_attempts', $public);
    }

    public function test_admin_can_view_and_update_cms_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('إعدادات المنصة وإدارة المحتوى');

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'platform_commission_rate' => '25',
            'site_name_ar' => 'أكاديمية بيفور بيم المطورة',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('cms_settings', [
            'key' => 'platform_commission_rate',
            'value' => '25',
        ]);
        $this->assertDatabaseHas('cms_settings', [
            'key' => 'site_name_ar',
            'value' => 'أكاديمية بيفور بيم المطورة',
        ]);
    }

    public function test_financial_report_service_calculates_revenue_and_kpis(): void
    {
        // Create completed order and payment
        $order = Order::create([
            'order_number' => 'ORD-1001',
            'user_id' => $this->student->id,
            'subtotal' => 400.00,
            'total_amount' => 400.00,
            'currency' => 'SAR',
            'status' => 'COMPLETED',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'title_snapshot' => $this->course->title_ar,
            'unit_price' => 400.00,
            'total_price' => 400.00,
        ]);

        Payment::create([
            'uuid' => (string) Str::uuid(),
            'order_id' => $order->id,
            'payment_method' => 'MADA',
            'gateway' => 'paymob',
            'amount' => 400.00,
            'currency' => 'SAR',
            'status' => 'SUCCESS',
            'created_at' => now(),
        ]);

        $service = app(FinancialReportService::class);
        $summary = $service->getExecutiveFinancialSummary();

        $this->assertEquals(400.00, $summary['gross_revenue']);
        $this->assertEquals(400.00, $summary['net_revenue']);
        $this->assertEquals(1, $summary['completed_orders_count']);
        $this->assertEquals(400.00, $summary['average_order_value']);
        $this->assertCount(1, $summary['top_courses']);
        $this->assertEquals($this->course->id, $summary['top_courses'][0]['course_id']);
    }

    public function test_instructor_earnings_calculation_respects_platform_commission(): void
    {
        // Set platform commission rate to 20%
        app(CmsSettingService::class)->set('platform_commission_rate', 20.0, 'payment', 'integer');

        $order = Order::create([
            'order_number' => 'ORD-1002',
            'user_id' => $this->student->id,
            'subtotal' => 500.00,
            'total_amount' => 500.00,
            'currency' => 'SAR',
            'status' => 'COMPLETED',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'title_snapshot' => $this->course->title_ar,
            'unit_price' => 500.00,
            'total_price' => 500.00,
        ]);

        $service = app(FinancialReportService::class);
        $earnings = $service->getInstructorEarnings($this->instructor);

        $this->assertEquals(500.00, $earnings['total_sales_amount']);
        $this->assertEquals(400.00, $earnings['instructor_earnings']); // 80% of 500
        $this->assertEquals(100.00, $earnings['platform_commission']); // 20% of 500
    }

    public function test_academic_analytics_calculates_completion_rates_and_funnels(): void
    {
        // Create enrollments with various progress percentages
        Enrollment::create([
            'user_id' => $this->student->id,
            'enrollable_type' => Course::class,
            'enrollable_id' => $this->course->id,
            'course_id' => $this->course->id,
            'source' => 'DIRECT_PURCHASE',
            'status' => 'ACTIVE',
            'progress_percentage' => 45.0,
            'enrolled_at' => now(),
        ]);

        $service = app(AcademicAnalyticsService::class);
        $summary = $service->getAcademicSummary();

        $this->assertEquals(1, $summary['total_enrollments']);
        $this->assertEquals(1, $summary['active_enrollments']);
        $this->assertEquals(45.0, $summary['average_progress']);
        $this->assertEquals(1, $summary['progress_funnel']['26_50']);
        $this->assertEquals(0, $summary['progress_funnel']['100']);
    }

    public function test_admin_can_access_report_endpoints_and_export_csv(): void
    {
        $this->actingAs($this->admin)->get(route('admin.reports.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.reports.financial'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.reports.academic'))->assertStatus(200);

        $csvResponse = $this->actingAs($this->admin)->get(route('admin.reports.export_csv'));
        $csvResponse->assertStatus(200);
        $csvResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_instructor_can_access_own_earnings_report(): void
    {
        $response = $this->actingAs($this->instructor)->get(route('instructor.reports.earnings'));
        $response->assertStatus(200);
        $response->assertSee('تقرير المستحقات ومبيعات المقررات');
    }
}
