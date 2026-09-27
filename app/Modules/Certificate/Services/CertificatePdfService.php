<?php

namespace App\Modules\Certificate\Services;

use App\Modules\Certificate\Models\Certificate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CertificatePdfService
{
    /**
     * Generate raw binary PDF (PDF-1.4) content for the given Certificate.
     */
    public function generate(Certificate $certificate): string
    {
        $studentName = $certificate->student_name_snapshot ?? ($certificate->user?->name ?? 'Engineer Student');
        $courseName = $certificate->course_title_snapshot_ar ?? ($certificate->course?->title_ar ?? 'BIM Engineering Masterclass');
        $instructorName = $certificate->instructor_name_snapshot ?? ($certificate->course?->instructor?->name ?? 'Lead BIM Consultant');
        $certNumber = $certificate->certificate_number ?? ('BFB-' . strtoupper(bin2hex(random_bytes(4))));
        $verifyCode = $certificate->verification_code ?? strtoupper(bin2hex(random_bytes(6)));
        $issueDate = $certificate->issued_at ? $certificate->issued_at->format('Y-m-d') : date('Y-m-d');
        $verifyUrl = $certificate->qr_verification_url ?: (config('app.url') . '/verify/' . $verifyCode);

        // Sanitize strings for standard PDF ASCII/WinAnsiEncoding
        $cleanStudent = $this->sanitizeForPdf($studentName);
        $cleanCourse = $this->sanitizeForPdf($courseName);
        $cleanInstructor = $this->sanitizeForPdf($instructorName);
        $cleanCertNum = $this->sanitizeForPdf($certNumber);
        $cleanVerifyCode = $this->sanitizeForPdf($verifyCode);
        $cleanDate = $this->sanitizeForPdf($issueDate);
        $cleanUrl = $this->sanitizeForPdf($verifyUrl);

        // Template customization if attached
        $template = $certificate->template;
        $primaryColorRgb = [7 / 255, 26 / 255, 54 / 255];    // #071A36 Deep Navy
        $secondaryColorRgb = [18 / 255, 59 / 255, 104 / 255]; // #123B68 Royal Blue
        $goldColorRgb = [212 / 255, 175 / 255, 55 / 255];     // #D4AF37 Gold Accent
        $lightGoldRgb = [243 / 255, 217 / 255, 139 / 255];    // #F3D98B Light Gold

        if ($template && is_array($template->design_config)) {
            if (isset($template->design_config['gold_color'])) {
                // optional hex override
            }
        }

        // Generate PDF streams
        return $this->buildPdfDocument([
            'student_name' => $cleanStudent,
            'course_title' => $cleanCourse,
            'instructor_name' => $cleanInstructor,
            'certificate_number' => $cleanCertNum,
            'verification_code' => $cleanVerifyCode,
            'issue_date' => $cleanDate,
            'verification_url' => $cleanUrl,
            'primary_rgb' => $primaryColorRgb,
            'secondary_rgb' => $secondaryColorRgb,
            'gold_rgb' => $goldColorRgb,
            'light_gold_rgb' => $lightGoldRgb,
        ]);
    }

    /**
     * Save the certificate PDF to persistent storage and update the certificate model.
     */
    public function save(Certificate $certificate, string $disk = 'public'): string
    {
        $pdfContent = $this->generate($certificate);
        $filePath = 'certificates/' . $certificate->certificate_number . '.pdf';

        Storage::disk($disk)->put($filePath, $pdfContent);

        $certificate->update(['pdf_storage_path' => $filePath]);

        return $filePath;
    }

    /**
     * Return a downloadable HTTP Response containing the PDF certificate.
     */
    public function download(Certificate $certificate): SymfonyResponse
    {
        $pdfContent = $this->generate($certificate);
        $filename = 'certificate-' . $certificate->certificate_number . '.pdf';

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($pdfContent),
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * Return an inline HTTP Response to preview the PDF certificate in browser.
     */
    public function stream(Certificate $certificate): SymfonyResponse
    {
        $pdfContent = $this->generate($certificate);
        $filename = 'certificate-' . $certificate->certificate_number . '.pdf';

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Content-Length' => strlen($pdfContent),
        ]);
    }

    /**
     * Sanitize and escape text for standard PDF literal string format.
     */
    protected function sanitizeForPdf(string $text): string
    {
        // Transliterate or clean non-ascii characters for standard Type 1 fonts
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if (empty($ascii) || strlen(trim($ascii)) === 0) {
            $ascii = preg_replace('/[^\x20-\x7E]/', '', $text);
        }
        if (empty(trim($ascii))) {
            $ascii = $text;
        }

        // Escape parentheses and backslashes for PDF string syntax
        $escaped = str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\\(', '\\)'],
            $ascii
        );

        return trim($escaped);
    }

    /**
     * Construct a valid, standalone PDF 1.4 binary file with vector graphics and typography.
     */
    protected function buildPdfDocument(array $data): string
    {
        // Dimensions: A4 Landscape (842 x 595 pt)
        $w = 842;
        $h = 595;

        $p = $data['primary_rgb'];
        $g = $data['gold_rgb'];
        $s = $data['secondary_rgb'];
        $lg = $data['light_gold_rgb'];

        // Content Stream (Draw borders, Beforbim logo, typography, seal, and QR code vector matrix)
        $cs = "";

        // 1. Background fill
        $cs .= "q\n";
        $cs .= "0.98 0.98 0.99 rg\n"; // very light soft gray
        $cs .= "0 0 {$w} {$h} re f\n";

        // 2. Outer Gold Border
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $g[0], $g[1], $g[2]);
        $cs .= "4 w\n";
        $cs .= "25 25 792 545 re S\n";

        // 3. Inner Deep Navy Border
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $p[0], $p[1], $p[2]);
        $cs .= "1.5 w\n";
        $cs .= "32 32 778 531 re S\n";

        // 4. Subtle Gold Accent Border
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $lg[0], $lg[1], $lg[2]);
        $cs .= "0.75 w\n";
        $cs .= "36 36 770 523 re S\n";

        // 5. Corner Ornaments (Engineering geometric corner brackets)
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $g[0], $g[1], $g[2]);
        $cs .= "2.5 w\n";
        // Top-Left
        $cs .= "45 520 m 45 550 l 75 550 l S\n";
        // Top-Right
        $cs .= "797 520 m 797 550 l 767 550 l S\n";
        // Bottom-Left
        $cs .= "45 75 m 45 45 l 75 45 l S\n";
        // Bottom-Right
        $cs .= "797 75 m 797 45 l 767 45 l S\n";

        // 6. Header Brand & Beforbim Logo Emblem
        // Geometric BIM Cube Emblem (Navy & Gold isometric cube vectors)
        $cx = 421;
        $cy = 505;
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $cs .= "{$cx} " . ($cy + 20) . " m " . ($cx - 18) . " " . ($cy + 10) . " l {$cx} {$cy} l " . ($cx + 18) . " " . ($cy + 10) . " l h f\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $s[0], $s[1], $s[2]);
        $cs .= ($cx - 18) . " " . ($cy + 10) . " m {$cx} {$cy} l {$cx} " . ($cy - 20) . " l " . ($cx - 18) . " " . ($cy - 10) . " l h f\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $g[0], $g[1], $g[2]);
        $cs .= "{$cx} {$cy} m " . ($cx + 18) . " " . ($cy + 10) . " l " . ($cx + 18) . " " . ($cy - 10) . " l {$cx} " . ($cy - 20) . " l h f\n";

        // BEFORBIM Brand Text
        $cs .= "BT\n";
        $cs .= "/F2 20 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $cs .= "365 470 Td (BEFORBIM) Tj\n";
        $cs .= "/F1 9 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $g[0], $g[1], $g[2]);
        $cs .= "-35 -14 Td (PREMIUM ENGINEERING LMS & BIM EXCELLENCE) Tj\n";
        $cs .= "ET\n";

        // Title: CERTIFICATE OF COMPLETION
        $cs .= "BT\n";
        $cs .= "/F2 26 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $s[0], $s[1], $s[2]);
        $cs .= "235 410 Td (CERTIFICATE OF COMPLETION) Tj\n";
        $cs .= "ET\n";

        // Subtitle line
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $g[0], $g[1], $g[2]);
        $cs .= "1 w\n";
        $cs .= "300 398 m 542 398 l S\n";

        // "This is to certify that"
        $cs .= "BT\n";
        $cs .= "/F1 11 Tf\n";
        $cs .= "0.3 0.35 0.4 rg\n";
        $cs .= "352 380 Td (THIS IS PROUDLY PRESENTED TO) Tj\n";
        $cs .= "ET\n";

        // Student Name
        $cs .= "BT\n";
        $cs .= "/F2 24 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $cs .= "250 345 Td (" . $data['student_name'] . ") Tj\n";
        $cs .= "ET\n";

        // Underline student name
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $lg[0], $lg[1], $lg[2]);
        $cs .= "1.5 w\n";
        $cs .= "220 335 m 622 335 l S\n";

        // Completion statement
        $cs .= "BT\n";
        $cs .= "/F1 11 Tf\n";
        $cs .= "0.3 0.35 0.4 rg\n";
        $cs .= "240 312 Td (for successfully completing the advanced BIM engineering curriculum and requirements of) Tj\n";
        $cs .= "ET\n";

        // Course Name
        $cs .= "BT\n";
        $cs .= "/F2 17 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $s[0], $s[1], $s[2]);
        $cs .= "240 285 Td (" . $data['course_title'] . ") Tj\n";
        $cs .= "ET\n";

        // Verification Badge & QR Block (Left)
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $g[0], $g[1], $g[2]);
        $cs .= "0.5 w\n";
        $cs .= "70 120 180 85 re S\n";
        $cs .= "0.96 0.97 0.98 rg\n";
        $cs .= "71 121 178 83 re f\n";

        // Simulated QR Code 2D matrix (vector pixel blocks)
        $qrX = 85;
        $qrY = 135;
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $this->drawQrMatrixPattern($cs, $qrX, $qrY);

        // Verification metadata next to QR
        $cs .= "BT\n";
        $cs .= "/F2 8 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $cs .= "145 185 Td (OFFICIAL VERIFICATION) Tj\n";
        $cs .= "/F1 7 Tf\n";
        $cs .= "0.2 0.25 0.3 rg\n";
        $cs .= "0 -13 Td (Cert No: " . $data['certificate_number'] . ") Tj\n";
        $cs .= "0 -11 Td (Code: " . $data['verification_code'] . ") Tj\n";
        $cs .= "0 -11 Td (Date: " . $data['issue_date'] . ") Tj\n";
        $cs .= "/F2 6 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $s[0], $s[1], $s[2]);
        $cs .= "0 -12 Td (verify.beforbim.com) Tj\n";
        $cs .= "ET\n";

        // Gold Official Seal (Center-Right)
        $sealX = 421;
        $sealY = 160;
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $g[0], $g[1], $g[2]);
        $cs .= "2 w\n";
        // Outer rosette circle
        $cs .= "{$sealX} {$sealY} 32 0 360 arc S\n";
        $cs .= "1 w\n";
        $cs .= "{$sealX} {$sealY} 28 0 360 arc S\n";
        $cs .= "BT\n";
        $cs .= "/F2 7 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $g[0], $g[1], $g[2]);
        $cs .= ($sealX - 22) . " " . ($sealY + 6) . " Td (BEFORBIM) Tj\n";
        $cs .= "/F1 6 Tf\n";
        $cs .= ($sealX - 24) . " " . ($sealY - 6) . " Td (SEAL OF QUALITY) Tj\n";
        $cs .= "ET\n";

        // Signatures (Right)
        // Instructor signature line
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $p[0], $p[1], $p[2]);
        $cs .= "1 w\n";
        $cs .= "580 150 m 750 150 l S\n";
        // Signature handwriting placeholder vector
        $cs .= sprintf("%.3F %.3F %.3F RG\n", $s[0], $s[1], $s[2]);
        $cs .= "1.2 w\n";
        $cs .= "590 162 m 615 178 l 640 158 l 660 175 l 690 160 l 730 170 l S\n";
        $cs .= "BT\n";
        $cs .= "/F2 10 Tf\n";
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $cs .= "590 135 Td (" . $data['instructor_name'] . ") Tj\n";
        $cs .= "/F1 8 Tf\n";
        $cs .= "0.4 0.45 0.5 rg\n";
        $cs .= "590 123 Td (Certified Lead BIM Instructor) Tj\n";
        $cs .= "ET\n";

        // Security footer bar
        $cs .= sprintf("%.3F %.3F %.3F rg\n", $p[0], $p[1], $p[2]);
        $cs .= "35 35 772 15 re f\n";
        $cs .= "BT\n";
        $cs .= "/F1 7 Tf\n";
        $cs .= "1 1 1 rg\n";
        $cs .= "180 39 Td (Verified Digital Credential - Beforbim Engineering Education Platform - URL: " . $data['verification_url'] . ") Tj\n";
        $cs .= "ET\n";

        $cs .= "Q\n";

        // Build PDF objects
        $objects = [];
        $streamLen = strlen($cs);

        // Obj 1: Catalog
        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";

        // Obj 2: Pages
        $objects[2] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";

        // Obj 3: Page (Landscape 842 x 595)
        $objects[3] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$w} {$h}] /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >>";

        // Obj 4: Contents Stream
        $objects[4] = "<< /Length {$streamLen} >>\nstream\n{$cs}\nendstream";

        // Obj 5: Font F1 (Helvetica)
        $objects[5] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>";

        // Obj 6: Font F2 (Helvetica-Bold)
        $objects[6] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>";

        // Assemble xref and body
        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];

        foreach ($objects as $num => $obj) {
            $offsets[$num] = strlen($pdf);
            $pdf .= "{$num} 0 obj\n{$obj}\nendobj\n";
        }

        $xrefPos = strlen($pdf);
        $totalObjs = count($objects) + 1;
        $pdf .= "xref\n0 {$totalObjs}\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size {$totalObjs} /Root 1 0 R >>\nstartxref\n{$xrefPos}\n%%EOF";

        return $pdf;
    }

    /**
     * Draw a 2D QR matrix pattern using PDF rectangle vectors.
     */
    protected function drawQrMatrixPattern(string &$cs, int $startX, int $startY): void
    {
        // 5x5 simulated micro-QR positioning grids and pattern
        $cellSize = 8;
        // Outer box 1
        $cs .= "{$startX} " . ($startY + 30) . " 15 15 re f\n";
        // Outer box 2
        $cs .= ($startX + 30) . " " . ($startY + 30) . " 15 15 re f\n";
        // Outer box 3
        $cs .= "{$startX} {$startY} 15 15 re f\n";

        // Center sync patterns
        $cs .= ($startX + 18) . " " . ($startY + 18) . " 8 8 re f\n";
        $cs .= ($startX + 28) . " " . ($startY + 5) . " 6 6 re f\n";
        $cs .= ($startX + 38) . " " . ($startY + 12) . " 6 6 re f\n";
        $cs .= ($startX + 5) . " " . ($startY + 20) . " 5 5 re f\n";
    }
}
