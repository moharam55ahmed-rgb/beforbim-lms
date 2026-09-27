<?php

use App\Modules\Lesson\Controllers\LessonPlayerController;
use Illuminate\Support\Facades\Route;

// Student Course Player & Learning Experience Routes
Route::prefix('learn')->group(function () {
    // Course Player (lesson is optional - defaults to first/next lesson)
    Route::get('/{course:slug}/{lesson?}', [LessonPlayerController::class, 'show'])->name('learn.player');

    // Progress Tracking API
    Route::post('/lessons/{lesson}/progress', [LessonPlayerController::class, 'saveProgress'])->name('learn.progress');
    Route::post('/lessons/{lesson}/toggle-complete', [LessonPlayerController::class, 'toggleComplete'])->name('learn.toggle_complete');

    // Resource Downloads
    Route::get('/resources/{resource}/download', [LessonPlayerController::class, 'downloadResource'])->name('learn.resource_download');

    // Secure Video Streaming
    Route::get('/contents/{content}/stream', [LessonPlayerController::class, 'videoStream'])->name('learn.video_stream');
});
