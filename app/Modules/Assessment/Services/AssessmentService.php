<?php

namespace App\Modules\Assessment\Services;

use App\Models\User;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\Assessment\Models\AssessmentQuestion;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\Course\Services\CourseCompletionService;
use App\Modules\ExamSecurity\Services\ExamSecurityService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AssessmentService
{
    public function __construct(
        protected CourseAccessService $accessService = new CourseAccessService,
        protected ExamSecurityService $securityService = new ExamSecurityService,
        protected ?CourseCompletionService $completionService = null
    ) {}

    /**
     * Check if a user can start or resume an attempt on this assessment.
     */
    public function checkAttemptEligibility(User $user, Assessment $assessment): array
    {
        $course = $assessment->course;

        if (! $this->accessService->canAccessCourse($user, $course)) {
            return ['allowed' => false, 'reason' => 'يجب أن تكون مسجلاً في الدورة لخوض الاختبار.'];
        }

        // Check if there is an in-progress attempt
        $inProgress = AssessmentAttempt::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->where('status', 'IN_PROGRESS')
            ->first();

        if ($inProgress) {
            return ['allowed' => true, 'attempt' => $inProgress, 'resumed' => true];
        }

        // Check max attempts
        $attemptsCount = AssessmentAttempt::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->count();

        if ($assessment->max_attempts > 0 && $attemptsCount >= $assessment->max_attempts) {
            return ['allowed' => false, 'reason' => 'لقد استنفدت الحد الأقصى للمحاولات المسموحة لهذا الاختبار.'];
        }

        return ['allowed' => true, 'attempt' => null, 'resumed' => false];
    }

    /**
     * Start a new assessment attempt.
     */
    public function startAttempt(User $user, Assessment $assessment): AssessmentAttempt
    {
        $check = $this->checkAttemptEligibility($user, $assessment);

        if (! $check['allowed']) {
            throw new InvalidArgumentException($check['reason'] ?? 'غير مسموح ببدء الاختبار.');
        }

        if (! empty($check['attempt'])) {
            return $check['attempt'];
        }

        $nextAttemptNumber = AssessmentAttempt::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->count() + 1;

        $totalPointsPossible = (float) $assessment->questions()->sum('points');

        return DB::transaction(function () use ($assessment, $user, $nextAttemptNumber, $totalPointsPossible) {
            $attempt = AssessmentAttempt::create([
                'assessment_id' => $assessment->id,
                'user_id' => $user->id,
                'attempt_number' => $nextAttemptNumber,
                'total_points_possible' => $totalPointsPossible > 0 ? $totalPointsPossible : 100.00,
                'total_points_earned' => 0.00,
                'score_percentage' => 0.00,
                'passed' => false,
                'status' => 'IN_PROGRESS',
                'anti_cheat_violations_count' => 0,
                'started_at' => now(),
            ]);

            AuditLog::log(
                'Assessment',
                'ASSESSMENT_ATTEMPT_STARTED',
                $user,
                $attempt,
                null,
                ['assessment_id' => $assessment->id, 'attempt_number' => $nextAttemptNumber]
            );

            return $attempt;
        });
    }

    /**
     * Submit an assessment attempt and run the automated grading engine.
     *
     * @param  array<int, mixed>  $answers  Key: question_id, Value: selected option id or text
     */
    public function submitAttempt(AssessmentAttempt $attempt, array $answers = []): AssessmentAttempt
    {
        if ($attempt->status !== 'IN_PROGRESS') {
            return $attempt;
        }

        return DB::transaction(function () use ($attempt, $answers) {
            $assessment = $attempt->assessment;
            $questions = $assessment->questions;

            $totalEarned = 0.00;
            $totalPossible = (float) $questions->sum('points');

            foreach ($questions as $question) {
                $qPoints = (float) $question->points;
                $userAnswer = $answers[$question->id] ?? null;

                if ($this->evaluateQuestionAnswer($question, $userAnswer)) {
                    $totalEarned += $qPoints;
                }
            }

            $scorePercentage = $totalPossible > 0
                ? round(($totalEarned / $totalPossible) * 100, 2)
                : 100.00;

            $passed = $scorePercentage >= (float) $assessment->passing_score_percentage;

            $finalStatus = $assessment->requires_manual_audit ? 'UNDER_MANUAL_REVIEW' : 'GRADED';

            $attempt->update([
                'total_points_possible' => $totalPossible,
                'total_points_earned' => $totalEarned,
                'score_percentage' => $scorePercentage,
                'passed' => $passed,
                'status' => $finalStatus,
                'submitted_at' => now(),
            ]);

            AuditLog::log(
                'Assessment',
                'ASSESSMENT_ATTEMPT_SUBMITTED',
                $attempt->user,
                $attempt,
                null,
                ['score_percentage' => $scorePercentage, 'passed' => $passed]
            );

            // If final certification exam passed, trigger course completion evaluation
            if ($passed && $assessment->type === 'FINAL_CERTIFICATION_EXAM') {
                $completionService = $this->completionService ?? app(CourseCompletionService::class);
                $completionService->processCompletion($attempt->user, $assessment->course);
            }

            return $attempt;
        });
    }

    /**
     * Evaluate if a student's answer to an assessment question is correct.
     */
    protected function evaluateQuestionAnswer(AssessmentQuestion $question, mixed $userAnswer): bool
    {
        if ($userAnswer === null) {
            return false;
        }

        $options = $question->options ?? [];

        if ($question->question_type === 'SINGLE_CHOICE' || $question->question_type === 'TRUE_FALSE') {
            foreach ($options as $opt) {
                if (! empty($opt['is_correct'])) {
                    // Match either by option ID or option text
                    if (isset($opt['id']) && (string) $opt['id'] === (string) $userAnswer) {
                        return true;
                    }
                    if (isset($opt['text_ar']) && (string) $opt['text_ar'] === (string) $userAnswer) {
                        return true;
                    }
                }
            }

            return false;
        }

        if ($question->question_type === 'MULTIPLE_CHOICE') {
            $userSelections = is_array($userAnswer) ? $userAnswer : [$userAnswer];
            $correctOptionIds = [];

            foreach ($options as $opt) {
                if (! empty($opt['is_correct'])) {
                    $correctOptionIds[] = (string) ($opt['id'] ?? $opt['text_ar']);
                }
            }

            sort($correctOptionIds);
            $userSelections = array_map('strval', $userSelections);
            sort($userSelections);

            return $correctOptionIds === $userSelections;
        }

        if ($question->question_type === 'NUMERICAL') {
            foreach ($options as $opt) {
                if (! empty($opt['is_correct']) && isset($opt['value'])) {
                    return abs((float) $opt['value'] - (float) $userAnswer) < 0.01;
                }
            }
        }

        return false;
    }

    /**
     * Instructor or Admin manually audits and updates an attempt.
     */
    public function auditAttempt(
        AssessmentAttempt $attempt,
        User $auditor,
        string $status,
        ?string $notes = null,
        ?bool $passed = null
    ): AssessmentAttempt {
        return DB::transaction(function () use ($attempt, $auditor, $status, $notes, $passed) {
            $updateData = [
                'status' => $status,
                'audited_by_user_id' => $auditor->id,
                'audit_notes' => $notes ?: $attempt->audit_notes,
            ];

            if ($passed !== null) {
                $updateData['passed'] = $passed;
            }

            $attempt->update($updateData);

            AuditLog::log(
                'Assessment',
                'ASSESSMENT_ATTEMPT_AUDITED',
                $auditor,
                $attempt,
                null,
                ['status' => $status, 'passed' => $attempt->passed]
            );

            return $attempt;
        });
    }
}
