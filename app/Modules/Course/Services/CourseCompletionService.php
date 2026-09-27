<?php

namespace App\Modules\Course\Services;

use App\Models\User;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\Assignment\Models\Assignment;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Certificate\Services\CertificateService;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Progress\Models\LessonProgress;
use App\Modules\Progress\Services\LessonProgressService;

class CourseCompletionService
{
    public function __construct(
        protected LessonProgressService $progressService,
        protected CertificateService $certificateService
    ) {}

    /**
     * Evaluate comprehensive course completion criteria for a student.
     */
    public function evaluateCriteria(User $user, Course $course, array $customRules = []): array
    {
        $rules = array_merge([
            'require_all_lessons' => true,
            'require_assessments' => true,
            'require_assignments' => true,
            'min_progress_percentage' => 100.00,
        ], $customRules);

        $reasons = [];

        // 1. Lesson Completion Evaluation
        $totalLessons = $course->lessons()->count();
        $completedLessonsCount = 0;
        $lessonsSatisfied = true;

        if ($totalLessons > 0) {
            $lessonIds = $course->lessons()->pluck('lessons.id');
            $completedLessonsCount = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->where('is_completed', true)
                ->count();

            if ($rules['require_all_lessons'] && $completedLessonsCount < $totalLessons) {
                $lessonsSatisfied = false;
                $remaining = $totalLessons - $completedLessonsCount;
                $reasons[] = "يتبقى عليك إكمال {$remaining} درس/محاضرة.";
            }
        }

        // 2. Course Progress Percentage
        $currentProgress = $totalLessons > 0
            ? round(($completedLessonsCount / $totalLessons) * 100, 2)
            : 100.00;

        $progressSatisfied = $currentProgress >= (float) $rules['min_progress_percentage'];
        if (! $progressSatisfied) {
            $reasons[] = "نسبة التقدم الحالية ({$currentProgress}%) أقل من الحد الأدنى المطلوب ({$rules['min_progress_percentage']}%).";
        }

        // 3. Assessment / Exam Requirements Evaluation
        $assessments = Assessment::where('course_id', $course->id)->get();
        $assessmentsSatisfied = true;

        if ($rules['require_assessments'] && $assessments->isNotEmpty()) {
            foreach ($assessments as $assessment) {
                $hasPassed = AssessmentAttempt::where('assessment_id', $assessment->id)
                    ->where('user_id', $user->id)
                    ->where('passed', true)
                    ->exists();

                if (! $hasPassed) {
                    $assessmentsSatisfied = false;
                    $reasons[] = "يجب اجتياز اختبار: {$assessment->title_ar}.";
                }
            }
        }

        // 4. Engineering Assignment Requirements Evaluation
        $assignments = Assignment::where('course_id', $course->id)->get();
        $assignmentsSatisfied = true;

        if ($rules['require_assignments'] && $assignments->isNotEmpty()) {
            foreach ($assignments as $assignment) {
                $hasPassed = AssignmentSubmission::where('assignment_id', $assignment->id)
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['GRADED', 'APPROVED'])
                    ->whereNotNull('grade')
                    ->where('grade', '>=', 50.00)
                    ->exists();

                if (! $hasPassed) {
                    $assignmentsSatisfied = false;
                    $reasons[] = "يجب تسليم واجتياز المشروع التطبيقي/الواجب: {$assignment->title_ar}.";
                }
            }
        }

        $isEligible = $lessonsSatisfied && $progressSatisfied && $assessmentsSatisfied && $assignmentsSatisfied;

        return [
            'is_eligible' => $isEligible,
            'progress_percentage' => $currentProgress,
            'completed_lessons' => $completedLessonsCount,
            'total_lessons' => $totalLessons,
            'criteria' => [
                'lessons_completed' => $lessonsSatisfied,
                'progress_met' => $progressSatisfied,
                'assessments_passed' => $assessmentsSatisfied,
                'assignments_passed' => $assignmentsSatisfied,
            ],
            'pending_reasons' => $reasons,
        ];
    }

    /**
     * Process course completion: update enrollment and issue certificate if eligible.
     */
    public function processCompletion(User $user, Course $course, array $customRules = []): array
    {
        $evaluation = $this->evaluateCriteria($user, $course, $customRules);

        if (! $evaluation['is_eligible']) {
            return array_merge($evaluation, [
                'certificate' => null,
                'message' => 'لم تستوفِ بعد كافة متطلبات إتمام هذه الدورة الهندسية.',
            ]);
        }

        // Update enrollment to 100% and record completion timestamp
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->update([
                'status' => 'ACTIVE',
                'progress_percentage' => 100.00,
                'completed_at' => $enrollment->completed_at ?? now(),
            ]);
        }

        // Calculate average grade if assessments/assignments exist
        $averageGrade = $this->calculateAggregateGrade($user, $course);

        // Issue official accredited certificate
        $certificate = $this->certificateService->issueCertificate($user, $course, $averageGrade);

        AuditLog::log(
            'Enrollment',
            'COURSE_COMPLETED',
            $user,
            $course,
            null,
            ['certificate_id' => $certificate->id, 'grade' => $averageGrade]
        );

        return array_merge($evaluation, [
            'certificate' => $certificate,
            'message' => 'تهانينا! لقد أتممت الدورة الهندسية بنجاح وتم إصدار شهادة إتمامك المعتمدة.',
        ]);
    }

    /**
     * Calculate aggregate grade across assessments and assignments.
     */
    protected function calculateAggregateGrade(User $user, Course $course): ?float
    {
        $grades = [];

        // Assessment scores
        $attempts = AssessmentAttempt::whereHas('assessment', fn ($q) => $q->where('course_id', $course->id))
            ->where('user_id', $user->id)
            ->where('passed', true)
            ->get();

        foreach ($attempts as $att) {
            $grades[] = (float) $att->score_percentage;
        }

        // Assignment grades
        $submissions = AssignmentSubmission::whereHas('assignment', fn ($q) => $q->where('course_id', $course->id))
            ->where('user_id', $user->id)
            ->whereNotNull('grade')
            ->get();

        foreach ($submissions as $sub) {
            $grades[] = (float) $sub->grade;
        }

        if (empty($grades)) {
            return 100.00; // Perfect completion score for pure lesson courses
        }

        return round(array_sum($grades) / count($grades), 2);
    }
}
