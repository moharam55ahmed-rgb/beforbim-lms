<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\AdminDashboardService;
use App\Modules\Auth\Services\InstructorDashboardService;
use App\Modules\Auth\Services\StudentDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected StudentDashboardService $studentDashboardService,
        protected InstructorDashboardService $instructorDashboardService,
        protected AdminDashboardService $adminDashboardService
    ) {}

    public function student(Request $request): View
    {
        $data = $this->studentDashboardService->getDashboardData($request->user());

        return view('dashboards.student', $data);
    }

    public function instructor(Request $request): View
    {
        $data = $this->instructorDashboardService->getDashboardData($request->user());

        return view('dashboards.instructor', $data);
    }

    public function admin(Request $request): View
    {
        $data = $this->adminDashboardService->getDashboardData();
        $data['user'] = $request->user();

        return view('dashboards.admin', $data);
    }
}

