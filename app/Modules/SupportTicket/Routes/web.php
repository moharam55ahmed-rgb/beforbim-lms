<?php

use App\Modules\SupportTicket\Controllers\AdminSupportTicketController;
use App\Modules\SupportTicket\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    // Student Support Tickets
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
        Route::post('/', [SupportTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('reply');
    });

    // Admin & Staff Helpdesk
    Route::prefix('admin/support')->name('admin.support.')->group(function () {
        Route::get('/', [AdminSupportTicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [AdminSupportTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [AdminSupportTicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/assign', [AdminSupportTicketController::class, 'assign'])->name('assign');
        Route::post('/{ticket}/status', [AdminSupportTicketController::class, 'updateStatus'])->name('status');
    });
});
