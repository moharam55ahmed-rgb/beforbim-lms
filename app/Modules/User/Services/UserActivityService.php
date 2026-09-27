<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\DeviceSession\Models\DeviceSession;
use App\Modules\Progress\Models\LessonProgress;
use Illuminate\Support\Collection;

class UserActivityService
{
    /**
     * Get active devices for the user.
     */
    public function getActiveDevices(User $user): Collection
    {
        return DeviceSession::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->latest('last_activity_at')
            ->get();
    }

    /**
     * Get recent course/lesson progress activity.
     */
    public function getRecentCourseActivity(User $user, int $limit = 5): Collection
    {
        return LessonProgress::query()
            ->where('user_id', $user->id)
            ->with(['lesson.section.course'])
            ->latest('updated_at')
            ->take($limit)
            ->get();
    }

    /**
     * Get recent exam/assessment attempts.
     */
    public function getRecentExams(User $user, int $limit = 5): Collection
    {
        return AssessmentAttempt::query()
            ->where('user_id', $user->id)
            ->with(['assessment.course'])
            ->latest('created_at')
            ->take($limit)
            ->get();
    }

    /**
     * Get recent assignment submissions.
     */
    public function getRecentAssignments(User $user, int $limit = 5): Collection
    {
        return AssignmentSubmission::query()
            ->where('user_id', $user->id)
            ->with(['assignment.course'])
            ->latest('created_at')
            ->take($limit)
            ->get();
    }

    /**
     * Get last login info.
     */
    public function getLastLogin(User $user): ?array
    {
        if ($user->last_login_at) {
            $latestDevice = DeviceSession::query()
                ->where('user_id', $user->id)
                ->latest('last_activity')
                ->first();

            return [
                'timestamp' => $user->last_login_at,
                'ip_address' => $latestDevice?->ip_address,
                'user_agent' => $latestDevice?->user_agent,
                'device_name' => $latestDevice?->device_name,
            ];
        }

        return null;
    }

    /**
     * Get a combined timeline of recent user activities.
     */
    public function getActivityTimeline(User $user, int $limit = 10): Collection
    {
        $timeline = collect();

        // 1. Lessons progress
        $lessons = $this->getRecentCourseActivity($user, $limit);
        foreach ($lessons as $item) {
            $courseTitle = $item->lesson?->section?->course?->title ?? 'كورس تدريبي';
            $lessonTitle = $item->lesson?->title ?? 'درس';
            $timeline->push([
                'type' => 'course_progress',
                'title' => $item->is_completed ? "إتمام الدرس: {$lessonTitle}" : "متابعة الدرس: {$lessonTitle}",
                'subtitle' => $courseTitle,
                'status' => $item->is_completed ? 'completed' : 'in_progress',
                'date' => $item->updated_at,
                'icon' => 'academic-cap',
            ]);
        }

        // 2. Exams
        $exams = $this->getRecentExams($user, $limit);
        foreach ($exams as $item) {
            $examTitle = $item->assessment?->title ?? 'اختبار تقييمي';
            $score = $item->score !== null ? "{$item->score}%" : 'قيد التصحيح';
            $timeline->push([
                'type' => 'assessment',
                'title' => "تقديم اختبار: {$examTitle}",
                'subtitle' => "النتيجة: {$score}",
                'status' => $item->status,
                'date' => $item->created_at,
                'icon' => 'clipboard-check',
            ]);
        }

        // 3. Assignments
        $assignments = $this->getRecentAssignments($user, $limit);
        foreach ($assignments as $item) {
            $assignmentTitle = $item->assignment?->title ?? 'مشروع هندسي';
            $timeline->push([
                'type' => 'assignment',
                'title' => "تسليم مشروع: {$assignmentTitle}",
                'subtitle' => $item->status === 'GRADED' ? "تم التقييم ({$item->score}/100)" : 'في انتظار المراجعة الهندسية',
                'status' => $item->status,
                'date' => $item->created_at,
                'icon' => 'document-arrow-up',
            ]);
        }

        // 4. Audit logs for security/login events
        $logs = AuditLog::query()
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->take($limit)
            ->get();

        foreach ($logs as $log) {
            $timeline->push([
                'type' => 'security',
                'title' => $log->action,
                'subtitle' => $log->ip_address,
                'status' => 'info',
                'date' => $log->created_at,
                'icon' => 'shield-check',
            ]);
        }

        return $timeline->sortByDesc('date')->values()->take($limit);
    }
}
