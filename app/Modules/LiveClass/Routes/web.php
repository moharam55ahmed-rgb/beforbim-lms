<?php

use App\Modules\LiveClass\Controllers\LiveClassController;
use Illuminate\Support\Facades\Route;

// Live Class Routes
Route::middleware(['web', 'auth', 'active.account'])->group(function () {
    // Per-course live class index & scheduling
    Route::prefix('courses/{course:slug}/live-classes')->group(function () {
        Route::get('/', [LiveClassController::class, 'index'])->name('live-class.index');
        Route::post('/', [LiveClassController::class, 'store'])->name('live-class.store');
    });

    // Single live class actions
    Route::prefix('live-classes/{liveClass}')->group(function () {
        Route::get('/', [LiveClassController::class, 'show'])->name('live-class.show');
        Route::get('/join', [LiveClassController::class, 'join'])->name('live-class.join');
        Route::post('/leave', [LiveClassController::class, 'leave'])->name('live-class.leave');
        Route::post('/complete', [LiveClassController::class, 'complete'])->name('live-class.complete');
        Route::post('/cancel', [LiveClassController::class, 'cancel'])->name('live-class.cancel');
    });
});
