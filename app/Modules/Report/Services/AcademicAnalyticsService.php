<?php

namespace App\Modules\Report\Services;

use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;

class AcademicAnalyticsService
{
    /**
     * Get high-level academic performance and learner progression metrics.
     *
     * @return array<string, mixed>
     */
    public function getAcademicSummary(): array
    {
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::where('status', 'ACTIVE')->count();
        $completedEnrollments = Enrollment::where('status', 'COMPLETED')->count();
        $suspendedEnrollments = Enrollment::where('status', 'SUSPENDED')->count();

        $overallCompletionRate = $totalEnrollments > 0
            ? round(($completedEnrollments / $totalEnrollments) * 100, 1)
            : 0.0;

        $averageProgress = (float) (Enrollment::avg('progress_percentage') ?? 0.0);

        // Progress Funnel Distribution
        $progressFunnel = [
            '0_25' => Enrollment::whereBetween('progress_percentage', [0, 25.99])->count(),
            '26_50' => Enrollment::whereBetween('progress_percentage', [26, 50.99])->count(),
            '51_75' => Enrollment::whereBetween('progress_percentage', [51, 75.99])->count(),
            '76_99' => Enrollment::whereBetween('progress_percentage', [76, 99.99])->count(),
            '100' => Enrollment::where('progress_percentage', '>=', 100)->count(),
        ];

        // Assessment Metrics
        $totalAttempts = AssessmentAttempt::count();
        $passedAttempts = AssessmentAttempt::where('passed', true)->count();
        $averageExamScore = (float) (AssessmentAttempt::avg('score_percentage') ?? 0.0);
        $passRate = $totalAttempts > 0
            ? round(($passedAttempts / $totalAttempts) * 100, 1)
            : 0.0;
        $totalProctoringViolations = (int) AssessmentAttempt::sum('anti_cheat_violations_count');

        // Top Enrolled Courses
        $topEnrolledCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit(5)
            ->get(['id', 'title_ar', 'title_en', 'enrollments_count'])
            ->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title_ar ?? $c->title_en,
                'enrollments_count' => $c->enrollments_count,
            ])
            ->toArray();

        return [
            'total_enrollments' => $totalEnrollments,
            'active_enrollments' => $activeEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'suspended_enrollments' => $suspendedEnrollments,
            'overall_completion_rate' => $overallCompletionRate,
            'average_progress' => round($averageProgress, 1),
            'progress_funnel' => $progressFunnel,
            'total_assessment_attempts' => $totalAttempts,
            'passed_attempts' => $passedAttempts,
            'average_exam_score' => round($averageExamScore, 1),
            'exam_pass_rate' => $passRate,
            'total_proctoring_violations' => $totalProctoringViolations,
            'top_enrolled_courses' => $topEnrolledCourses,
        ];
    }
}
