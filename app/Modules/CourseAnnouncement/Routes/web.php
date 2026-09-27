<?php

use App\Modules\CourseAnnouncement\Controllers\CourseAnnouncementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('courses/{course}/announcements')->name('courses.announcements.')->group(function () {
    Route::get('/', [CourseAnnouncementController::class, 'index'])->name('index');
    Route::post('/', [CourseAnnouncementController::class, 'store'])->name('store');
});
