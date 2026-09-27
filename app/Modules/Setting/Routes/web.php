<?php

use App\Modules\Setting\Controllers\AdminSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [AdminSettingController::class, 'index'])->name('index');
    Route::put('/', [AdminSettingController::class, 'update'])->name('update');
});
