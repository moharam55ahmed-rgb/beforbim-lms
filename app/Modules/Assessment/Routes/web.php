<?php

use App\Modules\Assessment\Controllers\AdminAssessmentController;
use App\Modules\Assessment\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'active.account'])->prefix('courses/{course:slug}/assessments')->group(function () {
    Route::get('/{assessment}', [AssessmentController::class, 'show'])->name('assessment.show');
    Route::post('/{assessment}/start', [AssessmentController::class, 'start'])->name('assessment.start');
    Route::get('/{assessment}/attempts/{attempt}', [AssessmentController::class, 'take'])->name('assessment.take');
    Route::post('/attempts/{attempt}/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::post('/attempts/{attempt}/security-violation', [AssessmentController::class, 'logSecurityViolation'])->name('assessment.security_violation');
    Route::get('/attempts/{attempt}/result', [AssessmentController::class, 'result'])->name('assessment.result');
});

// Admin Assessment & Certificate Management
Route::middleware(['web', 'auth', 'active.account'])->prefix('admin/assessments')->group(function () {
    Route::get('/', [AdminAssessmentController::class, 'index'])->name('admin.assessments.index');
    Route::get('/question-bank', [AdminAssessmentController::class, 'questionBank'])->name('admin.assessments.questions');
    Route::post('/question-bank', [AdminAssessmentController::class, 'storeQuestion'])->name('admin.assessments.questions.store');
    Route::put('/question-bank/{question}', [AdminAssessmentController::class, 'updateQuestion'])->name('admin.assessments.questions.update');
    Route::delete('/question-bank/{question}', [AdminAssessmentController::class, 'destroyQuestion'])->name('admin.assessments.questions.destroy');

    Route::get('/certificates', [AdminAssessmentController::class, 'certificates'])->name('admin.certificates.index');
    Route::post('/certificates/{certificate}/revoke', [AdminAssessmentController::class, 'revokeCertificate'])->name('admin.certificates.revoke');
    Route::post('/certificates/{certificate}/reissue', [AdminAssessmentController::class, 'reissueCertificate'])->name('admin.certificates.reissue');
});
