<?php

namespace App\Modules\Certificate\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CertificateService
{
    /**
     * Issue an official accredited certificate to an eligible student.
     */
    public function issueCertificate(User $user, Course $course, ?float $grade = null): Certificate
    {
        $existing = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return $existing;
        }

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        $verificationCode = strtoupper(Str::random(12));
        $certNumber = 'BFB-CERT-' . date('Y') . '-' . strtoupper(Str::random(8));

        $certificate = Certificate::create([
            'uuid' => (string) Str::uuid(),
            'certificate_number' => $certNumber,
            'verification_code' => $verificationCode,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment?->id,
            'student_name_snapshot' => $user->name,
            'course_title_snapshot_ar' => $course->title_ar,
            'course_title_snapshot_en' => $course->title_en ?: $course->title_ar,
            'instructor_name_snapshot' => $course->instructor?->name ?? 'مدرب المنصة المعتمد',
            'grade_percentage' => $grade,
            'issued_at' => now(),
            'status' => 'active',
            'is_revoked' => false,
            'qr_verification_url' => config('app.url') . '/verify/' . $verificationCode,
        ]);

        AuditLog::log(
            'Certificate',
            'CERTIFICATE_ISSUED',
            auth()->user() ?? $user,
            $certificate,
            null,
            ['certificate_number' => $certNumber, 'verification_code' => $verificationCode, 'grade' => $grade]
        );

        return $certificate;
    }

    /**
     * Publicly verify a certificate by certificate number, uuid, or verification code.
     */
    public function verifyCertificate(string $code): ?Certificate
    {
        $cleanCode = trim($code);

        return Certificate::where('verification_code', $cleanCode)
            ->orWhere('certificate_number', $cleanCode)
            ->orWhere('uuid', $cleanCode)
            ->with(['user', 'course.instructor'])
            ->first();
    }

    /**
     * Revoke a certificate in case of violation or administrative intervention.
     */
    public function revokeCertificate(Certificate $certificate, string $reason, ?User $admin = null): Certificate
    {
        $certificate->update([
            'status' => 'revoked',
            'is_revoked' => true,
            'revocation_reason' => $reason,
        ]);

        AuditLog::log(
            'Certificate',
            'CERTIFICATE_REVOKED',
            $admin ?? auth()->user(),
            $certificate,
            null,
            ['reason' => $reason]
        );

        return $certificate;
    }
}
