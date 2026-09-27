<?php

use App\Modules\CourseDiscussion\Controllers\CourseDiscussionController;
use Illuminate\Support\Facades\Route;

// Course Discussion & Community Q&A Routes
Route::middleware(['auth', 'active.account'])->prefix('learn/courses/{course}')->group(function () {
    Route::post('/discussions', [CourseDiscussionController::class, 'store'])->name('learn.discussions.store');
});
