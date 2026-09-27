<?php

namespace App\Modules\Report\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Payment\Models\Payment;
use App\Modules\Setting\Services\CmsSettingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportService
{
    public function __construct(
        protected CmsSettingService $settingService
    ) {}

    /**
     * Get executive financial summary.
     *
     * @return array<string, mixed>
     */
    public function getExecutiveFinancialSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $grossRevenue = (float) Payment::where('status', 'SUCCESS')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $refundedAmount = (float) Payment::where('status', 'REFUNDED')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $netRevenue = $grossRevenue - $refundedAmount;

        $completedOrdersCount = Order::where('status', 'COMPLETED')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $averageOrderValue = $completedOrdersCount > 0 ? round($grossRevenue / $completedOrdersCount, 2) : 0.00;

        // Payment method breakdown
        $paymentMethods = Payment::where('status', 'SUCCESS')
            ->whereBetween('created_at', [$start, $end])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->map(fn ($row) => [
                'method' => $row->payment_method,
                'count' => (int) $row->count,
                'total' => (float) $row->total,
            ])
            ->toArray();

        // Top earning courses
        $topCourses = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->where('status', 'COMPLETED')
                ->whereBetween('created_at', [$start, $end]);
        })
            ->select('course_id', 'title_snapshot', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('course_id', 'title_snapshot')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'course_id' => $item->course_id,
                'title' => $item->title_snapshot,
                'sales_count' => (int) $item->sales_count,
                'total_revenue' => (float) $item->total_revenue,
            ])
            ->toArray();

        // Daily trend
        $dailyTrend = Payment::where('status', 'SUCCESS')
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as revenue'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($d) => [
                'date' => $d->date,
                'revenue' => (float) $d->revenue,
                'orders_count' => (int) $d->orders_count,
            ])
            ->toArray();

        return [
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'gross_revenue' => $grossRevenue,
            'refunded_amount' => $refundedAmount,
            'net_revenue' => $netRevenue,
            'completed_orders_count' => $completedOrdersCount,
            'average_order_value' => $averageOrderValue,
            'payment_methods' => $paymentMethods,
            'top_courses' => $topCourses,
            'daily_trend' => $dailyTrend,
        ];
    }

    /**
     * Get instructor earnings and payout breakdown.
     *
     * @return array<string, mixed>
     */
    public function getInstructorEarnings(User $instructor, ?string $startDate = null, ?string $endDate = null): array
    {
        $platformFeeRate = (float) $this->settingService->get('platform_commission_rate', 20.0);
        $instructorRate = max(0.0, 100.0 - $platformFeeRate);

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $instructorCourseIds = Course::where('instructor_id', $instructor->id)->pluck('id');

        $items = OrderItem::whereIn('course_id', $instructorCourseIds)
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->where('status', 'COMPLETED')
                    ->whereBetween('created_at', [$start, $end]);
            })
            ->with('course')
            ->get();

        $totalSalesAmount = (float) $items->sum('total_price');
        $instructorEarnings = round($totalSalesAmount * ($instructorRate / 100.0), 2);
        $platformCommission = round($totalSalesAmount * ($platformFeeRate / 100.0), 2);

        $coursesBreakdown = [];
        $grouped = $items->groupBy('course_id');

        foreach ($grouped as $cId => $courseItems) {
            $course = $courseItems->first()->course;
            $courseSales = (float) $courseItems->sum('total_price');
            $coursesBreakdown[] = [
                'course_id' => $cId,
                'title' => $course ? $course->title_ar : ($courseItems->first()->title_snapshot ?? 'Course #'.$cId),
                'sales_count' => $courseItems->count(),
                'gross_revenue' => $courseSales,
                'instructor_share' => round($courseSales * ($instructorRate / 100.0), 2),
            ];
        }

        return [
            'instructor_id' => $instructor->id,
            'instructor_name' => $instructor->name,
            'platform_commission_rate' => $platformFeeRate,
            'instructor_share_rate' => $instructorRate,
            'total_sales_amount' => $totalSalesAmount,
            'instructor_earnings' => $instructorEarnings,
            'platform_commission' => $platformCommission,
            'courses_breakdown' => $coursesBreakdown,
            'total_sales_count' => $items->count(),
        ];
    }
}
