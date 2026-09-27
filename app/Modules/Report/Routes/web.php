<?php

use App\Modules\Report\Controllers\AdminReportController;
use App\Modules\Report\Controllers\InstructorReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    // Admin Executive & Financial Reports
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/financial', [AdminReportController::class, 'financial'])->name('financial');
        Route::get('/academic', [AdminReportController::class, 'academic'])->name('academic');
        Route::get('/export-csv', [AdminReportController::class, 'exportFinancialCsv'])->name('export_csv');
    });

    // Instructor Reports
    Route::prefix('instructor/reports')->name('instructor.reports.')->group(function () {
        Route::get('/earnings', [InstructorReportController::class, 'earnings'])->name('earnings');
    });
});
