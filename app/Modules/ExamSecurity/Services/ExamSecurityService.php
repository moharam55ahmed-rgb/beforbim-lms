<?php

namespace App\Modules\ExamSecurity\Services;

use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\ExamSecurity\Models\ExamSecurityEvent;
use Illuminate\Support\Facades\DB;

class ExamSecurityService
{
    /**
     * Record an anti-cheat security violation event during an exam attempt.
     */
    public function logViolation(
        AssessmentAttempt $attempt,
        string $eventType,
        ?string $ipAddress = null,
        ?string $details = null
    ): ExamSecurityEvent {
        return DB::transaction(function () use ($attempt, $eventType, $ipAddress, $details) {
            $event = ExamSecurityEvent::create([
                'attempt_id' => $attempt->id,
                'event_type' => $eventType,
                'severity' => in_array($eventType, ['FULLSCREEN_EXIT', 'TAB_BLUR']) ? 'WARNING' : 'VIOLATION',
                'details' => $details,
                'ip_address' => $ipAddress,
                'occurred_at' => now(),
            ]);

            $attempt->increment('anti_cheat_violations_count');
            $attempt->refresh();

            $maxAllowed = $attempt->assessment->max_violations_allowed ?? 3;

            // Auto-disqualify attempt if violations threshold breached
            if ($attempt->anti_cheat_violations_count >= $maxAllowed && $attempt->status === 'IN_PROGRESS') {
                $attempt->update([
                    'status' => 'DISQUALIFIED',
                    'passed' => false,
                    'submitted_at' => now(),
                    'audit_notes' => "تم استبعاد المحاولة تلقائياً لتجاوز الحد الأقصى للمخالفات الأمنية ({$maxAllowed} مخالفات).",
                ]);

                AuditLog::log(
                    'ExamSecurity',
                    'EXAM_ATTEMPT_DISQUALIFIED',
                    $attempt->user,
                    $attempt,
                    null,
                    ['violations_count' => $attempt->anti_cheat_violations_count, 'last_event' => $eventType]
                );
            }

            return $event;
        });
    }
}
