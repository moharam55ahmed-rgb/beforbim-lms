<?php

use App\Modules\Certificate\Controllers\CertificateController;
use App\Modules\Certificate\Controllers\CertificateVerificationController;
use Illuminate\Support\Facades\Route;

// Public Certificate Verification Route
Route::middleware(['web'])->get('/verify/{code?}', [CertificateVerificationController::class, 'verify'])->name('certificate.verify');

// Certificate PDF Download Routes
Route::middleware(['web'])->group(function () {
    Route::get('/verify/{code}/download', [CertificateController::class, 'downloadPublic'])->name('certificate.public.download');
    Route::middleware(['auth'])->get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
});
