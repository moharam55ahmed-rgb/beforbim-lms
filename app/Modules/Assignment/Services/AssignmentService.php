<?php

namespace App\Modules\Assignment\Services;

use App\Models\User;
use App\Modules\Assignment\Models\Assignment;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\Notification\Events\AssignmentGraded;
use App\Modules\Notification\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AssignmentService
{
    public function __construct(
        protected CourseAccessService $accessService = new CourseAccessService(),
        protected NotificationService $notificationService = new NotificationService()
    ) {}

    /**
     * Submit student work for an engineering assignment.
     */
    public function submitAssignment(
        User $user,
        Assignment $assignment,
        ?string $studentNotes,
        string $filePath,
        string $fileName,
        int $fileSizeBytes
    ): AssignmentSubmission {
        $course = $assignment->course;

        if (! $this->accessService->canAccessCourse($user, $course)) {
            throw new InvalidArgumentException('يجب أن تكون مشتركاً في الدورة لتقديم الواجب الهندسي.');
        }

        return DB::transaction(function () use ($user, $assignment, $studentNotes, $filePath, $fileName, $fileSizeBytes) {
            $submission = AssignmentSubmission::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'user_id' => $user->id,
                ],
                [
                    'student_notes' => $studentNotes,
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'file_size_bytes' => $fileSizeBytes,
                    'status' => 'SUBMITTED',
                    'submitted_at' => now(),
                ]
            );

            AuditLog::log(
                'Assignment',
                'ASSIGNMENT_SUBMITTED',
                $user,
                $assignment,
                null,
                ['submission_id' => $submission->id, 'file_name' => $fileName]
            );

            return $submission;
        });
    }

    /**
     * Instructor or Admin grades an engineering submission and provides constructive feedback.
     */
    public function gradeSubmission(
        AssignmentSubmission $submission,
        User $instructor,
        float $grade,
        ?string $feedback = null
    ): AssignmentSubmission {
        $assignment = $submission->assignment;
        $maxPoints = (float) $assignment->total_points;

        if ($grade < 0 || $grade > $maxPoints) {
            throw new InvalidArgumentException("الدرجة يجب أن تكون بين 0 و {$maxPoints}.");
        }

        return DB::transaction(function () use ($submission, $instructor, $grade, $feedback) {
            $submission->update([
                'grade' => $grade,
                'instructor_feedback' => $feedback,
                'status' => 'GRADED',
                'graded_by_user_id' => $instructor->id,
                'graded_at' => now(),
            ]);

            AuditLog::log(
                'Assignment',
                'ASSIGNMENT_GRADED',
                $instructor,
                $submission,
                null,
                ['grade' => $grade, 'student_id' => $submission->user_id]
            );

            // Dispatch domain event & trigger notification
            $event = new AssignmentGraded($submission);
            event($event);
            $this->notificationService->handleAssignmentGraded($event);

            return $submission;
        });
    }

    /**
     * Grade an assignment submission using multi-criteria rubric evaluation.
     *
     * @param array<int, float|array{score: float, comment?: string}> $rubricScores [rubric_id => score]
     */
    public function gradeSubmissionWithRubrics(
        AssignmentSubmission $submission,
        User $instructor,
        array $rubricScores,
        ?string $feedback = null
    ): AssignmentSubmission {
        $assignment = $submission->assignment;
        $rubrics = $assignment->rubrics()->get()->keyBy('id');

        if ($rubrics->isEmpty()) {
            throw new InvalidArgumentException('لا توجد معايير تقييم (Rubrics) محددة لهذا الواجب.');
        }

        $totalGrade = 0.0;
        $rubricBreakdown = [];

        foreach ($rubricScores as $rubricId => $entry) {
            $rubric = $rubrics->get($rubricId);
            if (! $rubric) {
                continue;
            }

            $score = is_array($entry) ? (float) ($entry['score'] ?? 0) : (float) $entry;
            $comment = is_array($entry) ? ($entry['comment'] ?? null) : null;

            if ($score < 0 || $score > (float) $rubric->max_score) {
                throw new InvalidArgumentException("الدرجة لمعيار '{$rubric->criteria}' يجب أن تكون بين 0 و {$rubric->max_score}.");
            }

            $totalGrade += $score;
            $rubricBreakdown[] = [
                'rubric_id' => $rubric->id,
                'criteria' => $rubric->criteria,
                'score' => $score,
                'max_score' => (float) $rubric->max_score,
                'weight' => (float) $rubric->weight,
                'comment' => $comment,
            ];
        }

        // Cap to total points if specified
        $maxPoints = (float) $assignment->total_points;
        if ($totalGrade > $maxPoints) {
            $totalGrade = $maxPoints;
        }

        $composedFeedback = $feedback;
        if (! empty($rubricBreakdown)) {
            $breakdownSummary = "\n\n--- تفاصيل تقييم المعايير الهندسية (Rubric Assessment) ---\n";
            foreach ($rubricBreakdown as $b) {
                $breakdownSummary .= "- {$b['criteria']}: {$b['score']} / {$b['max_score']}";
                if (! empty($b['comment'])) {
                    $breakdownSummary .= " ({$b['comment']})";
                }
                $breakdownSummary .= "\n";
            }
            $composedFeedback = trim(($feedback ?? '') . $breakdownSummary);
        }

        return $this->gradeSubmission($submission, $instructor, $totalGrade, $composedFeedback);
    }
}
