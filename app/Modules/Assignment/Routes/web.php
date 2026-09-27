<?php

use App\Modules\Assignment\Controllers\AssignmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'active.account'])->prefix('courses/{course:slug}/assignments')->group(function () {
    Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('assignment.show');
    Route::post('/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignment.submit');
    Route::post('/submissions/{submission}/grade', [AssignmentController::class, 'grade'])->name('assignment.grade');
});
