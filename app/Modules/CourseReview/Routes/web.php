<?php

use App\Modules\CourseReview\Controllers\CourseReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    // Student Review Submission
    Route::post('courses/{course}/reviews', [CourseReviewController::class, 'store'])->name('courses.reviews.store');

    // Admin Review Moderation
    Route::prefix('admin/reviews')->name('admin.reviews.')->group(function () {
        Route::get('/', [CourseReviewController::class, 'adminIndex'])->name('index');
        Route::post('/{review}/moderate', [CourseReviewController::class, 'moderate'])->name('moderate');
    });
});
