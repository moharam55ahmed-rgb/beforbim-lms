<?php

use App\Modules\Course\Controllers\AdminCourseApprovalController;
use App\Modules\Course\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

// Course Web Routes

// Public / Authenticated Course Index & Details
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Instructor & Admin Course Management
Route::middleware(['auth', 'role:super_admin,admin,instructor'])->group(function () {
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::post('/courses/{course}/submit', [CourseController::class, 'submit'])->name('courses.submit');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
});

// Admin Course Approval Workflow
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/courses/pending', [AdminCourseApprovalController::class, 'pending'])->name('courses.pending');
    Route::get('/courses/{course}/review', [AdminCourseApprovalController::class, 'show'])->name('courses.review');
    Route::post('/courses/{course}/approve', [AdminCourseApprovalController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/reject', [AdminCourseApprovalController::class, 'reject'])->name('courses.reject');
});
