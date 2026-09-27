<?php

namespace App\Modules\Report\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Models\Payment;
use App\Modules\Report\Services\AcademicAnalyticsService;
use App\Modules\Report\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function __construct(
        protected FinancialReportService $financialReportService,
        protected AcademicAnalyticsService $academicAnalyticsService
    ) {}

    /**
     * Executive Overview Hub.
     */
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $financial = $this->financialReportService->getExecutiveFinancialSummary($startDate, $endDate);
        $academic = $this->academicAnalyticsService->getAcademicSummary();

        return view('admin.reports.index', [
            'financial' => $financial,
            'academic' => $academic,
        ]);
    }

    /**
     * Dedicated Financial Ledger & Gateway Reconciliation.
     */
    public function financial(Request $request): View
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $financial = $this->financialReportService->getExecutiveFinancialSummary($startDate, $endDate);

        $payments = Payment::with(['order.user'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.financial', [
            'financial' => $financial,
            'payments' => $payments,
        ]);
    }

    /**
     * Dedicated Academic Performance & Retention Analytics.
     */
    public function academic(): View
    {
        $academic = $this->academicAnalyticsService->getAcademicSummary();

        return view('admin.reports.academic', [
            'academic' => $academic,
        ]);
    }

    /**
     * Export completed payments to CSV.
     */
    public function exportFinancialCsv(Request $request): StreamedResponse
    {
        $payments = Payment::with(['order.user'])
            ->where('status', 'SUCCESS')
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="beforbim_financial_report_'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($payments) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Arabic support in Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['رقم العملية', 'رقم الطلب', 'اسم العميل', 'البريد الإلكتروني', 'المبلغ', 'العملة', 'طريقة الدفع', 'بوابة الدفع', 'تاريخ التحصيل']);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->uuid,
                    $payment->order?->order_number ?? 'N/A',
                    $payment->order?->user?->name ?? 'عميل',
                    $payment->order?->user?->email ?? '',
                    $payment->amount,
                    $payment->currency,
                    $payment->payment_method,
                    $payment->gateway,
                    $payment->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
