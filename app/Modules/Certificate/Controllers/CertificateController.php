<?php

namespace App\Modules\Certificate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Certificate\Services\CertificatePdfService;
use App\Modules\Certificate\Services\CertificateService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function __construct(
        protected CertificateService $certificateService,
        protected CertificatePdfService $pdfService
    ) {}

    /**
     * Download the certificate PDF for an authorized user or student owner.
     */
    public function download(Certificate $certificate): Response
    {
        $user = auth()->user();

        // Check permission: Student owner, Course instructor, or Admin
        if (! $user) {
            abort(401);
        }

        $isOwner = $certificate->user_id === $user->id;
        $isCourseInstructor = $certificate->course?->instructor_id === $user->id;
        $isAdmin = in_array($user->role, ['admin', 'superadmin']);

        if (! $isOwner && ! $isCourseInstructor && ! $isAdmin) {
            abort(403, 'غير مصرح لك بتحميل هذه الشهادة.');
        }

        return $this->pdfService->download($certificate);
    }

    /**
     * Public download of an active verified certificate by verification code.
     */
    public function downloadPublic(string $code): Response
    {
        $certificate = $this->certificateService->verifyCertificate($code);

        if (! $certificate || ! $certificate->isActive()) {
            abort(404, 'الشهادة المطلوبة غير موجودة أو ملغاة.');
        }

        return $this->pdfService->download($certificate);
    }
}
