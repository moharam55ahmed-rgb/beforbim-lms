<?php

use App\Modules\Media\Controllers\AdminMediaController;
use Illuminate\Support\Facades\Route;

// Media Library Admin Routes
Route::middleware(['web', 'auth', 'role:super_admin,admin'])->prefix('admin/media')->name('admin.media.')->group(function () {
    Route::get('/', [AdminMediaController::class, 'index'])->name('index');
    Route::post('/', [AdminMediaController::class, 'store'])->name('store');
    Route::delete('/{media}', [AdminMediaController::class, 'destroy'])->name('destroy');
});
