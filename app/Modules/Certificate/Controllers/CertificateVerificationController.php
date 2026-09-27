<?php

namespace App\Modules\Certificate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Certificate\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function __construct(
        protected CertificateService $certificateService
    ) {}

    /**
     * Public certificate verification page.
     */
    public function verify(Request $request, ?string $code = null)
    {
        $searchCode = $code ?: $request->query('code');
        $certificate = null;

        if ($searchCode) {
            $certificate = $this->certificateService->verifyCertificate($searchCode);
        }

        return view('certificate.verify', compact('certificate', 'searchCode'));
    }
}
