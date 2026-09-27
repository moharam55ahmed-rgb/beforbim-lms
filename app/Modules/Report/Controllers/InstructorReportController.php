<?php

namespace App\Modules\Report\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Report\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorReportController extends Controller
{
    public function __construct(
        protected FinancialReportService $financialReportService
    ) {}

    /**
     * Display instructor earnings and payout breakdown.
     */
    public function earnings(Request $request): View
    {
        $user = $request->user();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $earnings = $this->financialReportService->getInstructorEarnings($user, $startDate, $endDate);

        return view('instructor.reports.earnings', [
            'earnings' => $earnings,
        ]);
    }
}
