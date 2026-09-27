<?php

use App\Modules\AuditLog\Controllers\AdminAuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin/audit-logs')->name('admin.audit-logs.')->group(function () {
    Route::get('/', [AdminAuditLogController::class, 'index'])->name('index');
    Route::get('/{auditLog}', [AdminAuditLogController::class, 'show'])->name('show');
});
