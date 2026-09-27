<?php

use App\Modules\Curriculum\Controllers\CurriculumController;
use Illuminate\Support\Facades\Route;

// Curriculum Web Routes
Route::middleware(['auth', 'role:super_admin,admin,instructor'])->group(function () {
    // Curriculum Builder
    Route::get('/courses/{course}/curriculum', [CurriculumController::class, 'builder'])->name('courses.curriculum');

    // Section Management
    Route::post('/courses/{course}/sections', [CurriculumController::class, 'storeSection'])->name('courses.sections.store');
    Route::put('/sections/{section}', [CurriculumController::class, 'updateSection'])->name('courses.sections.update');
    Route::delete('/sections/{section}', [CurriculumController::class, 'destroySection'])->name('courses.sections.destroy');
    Route::post('/courses/{course}/sections/reorder', [CurriculumController::class, 'reorderSections'])->name('courses.sections.reorder');

    // Lesson Management
    Route::post('/sections/{section}/lessons', [CurriculumController::class, 'storeLesson'])->name('courses.lessons.store');
    Route::put('/lessons/{lesson}', [CurriculumController::class, 'updateLesson'])->name('courses.lessons.update');
    Route::delete('/lessons/{lesson}', [CurriculumController::class, 'destroyLesson'])->name('courses.lessons.destroy');
    Route::post('/sections/{section}/lessons/reorder', [CurriculumController::class, 'reorderLessons'])->name('courses.lessons.reorder');

    // Lesson Resources
    Route::post('/lessons/{lesson}/resources', [CurriculumController::class, 'storeResource'])->name('courses.resources.store');
    Route::delete('/resources/{resource}', [CurriculumController::class, 'destroyResource'])->name('courses.resources.destroy');
});
