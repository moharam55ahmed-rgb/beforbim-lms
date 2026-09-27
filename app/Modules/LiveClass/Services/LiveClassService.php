<?php

namespace App\Modules\LiveClass\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\LiveClass\Models\LiveClass;
use App\Modules\LiveClass\Models\LiveClassAttendance;
use App\Modules\Notification\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LiveClassService
{
    public function __construct(
        protected LiveClassProviderManager $providerManager,
        protected CourseAccessService $accessService,
        protected NotificationService $notificationService
    ) {}

    /**
     * Schedule a new live engineering class session.
     */
    public function scheduleLiveClass(
        Course $course,
        User $instructor,
        array $details
    ): LiveClass {
        if (! $this->accessService->canManageCourse($instructor, $course)) {
            throw new InvalidArgumentException('ليس لديك صلاحية جدولة حصص مباشرة لهذه الدورة.');
        }

        $provider = strtoupper($details['provider'] ?? 'ZOOM');
        $providerDriver = $this->providerManager->driver($provider);

        $meetingData = $providerDriver->createMeeting([
            'topic' => $details['title_ar'] ?? ($course->title_ar . ' — حصة مباشرة'),
            'start_time' => $details['scheduled_start_time'] ?? now()->addDay()->toIso8601String(),
            'duration_minutes' => (int) ($details['duration_minutes'] ?? 90),
            'password' => $details['meeting_password'] ?? null,
        ]);

        return DB::transaction(function () use ($course, $instructor, $details, $provider, $meetingData) {
            $liveClass = LiveClass::create([
                'course_id' => $course->id,
                'instructor_id' => $instructor->id,
                'title_ar' => $details['title_ar'],
                'title_en' => $details['title_en'] ?? null,
                'description_ar' => $details['description_ar'] ?? null,
                'provider' => $provider,
                'meeting_id' => $meetingData['meeting_id'],
                'meeting_password' => $meetingData['meeting_password'] ?? null,
                'join_url_student' => $meetingData['join_url'],
                'host_url_instructor' => $meetingData['host_url'] ?? null,
                'scheduled_start_time' => $details['scheduled_start_time'],
                'duration_minutes' => (int) ($details['duration_minutes'] ?? 90),
                'status' => 'SCHEDULED',
            ]);

            AuditLog::log('LiveClass', 'LIVE_CLASS_SCHEDULED', $instructor, $liveClass, null, [
                'course_id' => $course->id,
                'provider' => $provider,
                'start_time' => $liveClass->scheduled_start_time,
            ]);

            // Notify enrolled students
            $this->notifyEnrolledStudents($liveClass, $course);

            return $liveClass;
        });
    }

    /**
     * Authorize and generate a student's join URL for a live class.
     */
    public function authorizeStudentJoin(LiveClass $liveClass, User $user): string
    {
        $course = $liveClass->course;

        if (! $this->accessService->canAccessCourse($user, $course)) {
            throw new InvalidArgumentException('يجب أن تكون مشتركاً في الدورة للانضمام إلى الحصة المباشرة.');
        }

        if (in_array($liveClass->status, ['COMPLETED', 'CANCELLED'])) {
            throw new InvalidArgumentException('هذه الحصة المباشرة قد انتهت أو تم إلغاؤها.');
        }

        // Log attendance join event
        $attendance = LiveClassAttendance::firstOrCreate(
            [
                'live_class_id' => $liveClass->id,
                'user_id' => $user->id,
            ],
            [
                'joined_at' => now(),
                'attended_minutes' => 0,
            ]
        );

        if ($attendance->wasRecentlyCreated) {
            AuditLog::log('LiveClass', 'STUDENT_JOINED', $user, $liveClass);
        }

        return $liveClass->join_url_student;
    }

    /**
     * Log a student leaving the live class and compute attended minutes.
     */
    public function recordStudentLeave(LiveClass $liveClass, User $user): int
    {
        $attendance = LiveClassAttendance::where('live_class_id', $liveClass->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $attendance || $attendance->left_at) {
            return 0;
        }

        $minutesAttended = (int) round(abs(now()->diffInMinutes($attendance->joined_at)));
        $attendance->update([
            'left_at' => now(),
            'attended_minutes' => $minutesAttended,
        ]);

        AuditLog::log('LiveClass', 'STUDENT_LEFT', $user, $liveClass, null, [
            'attended_minutes' => $minutesAttended,
        ]);

        return $minutesAttended;
    }

    /**
     * Mark a live class as completed.
     */
    public function completeLiveClass(LiveClass $liveClass, User $instructor, ?string $recordingUrl = null): LiveClass
    {
        $liveClass->update([
            'status' => 'COMPLETED',
            'recording_url' => $recordingUrl,
        ]);

        AuditLog::log('LiveClass', 'LIVE_CLASS_COMPLETED', $instructor, $liveClass, null, [
            'recording_url' => $recordingUrl,
        ]);

        return $liveClass;
    }

    /**
     * Cancel a scheduled live class.
     */
    public function cancelLiveClass(LiveClass $liveClass, User $instructor): LiveClass
    {
        $this->providerManager->driver($liveClass->provider)->cancelMeeting($liveClass->meeting_id);

        $liveClass->update(['status' => 'CANCELLED']);

        AuditLog::log('LiveClass', 'LIVE_CLASS_CANCELLED', $instructor, $liveClass);

        return $liveClass;
    }

    /**
     * Get upcoming live classes for a course.
     */
    public function getUpcomingForCourse(Course $course, int $limit = 5): \Illuminate\Support\Collection
    {
        return LiveClass::where('course_id', $course->id)
            ->where('status', 'SCHEDULED')
            ->where('scheduled_start_time', '>=', now())
            ->orderBy('scheduled_start_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Notify all enrolled students about a newly scheduled live class.
     */
    protected function notifyEnrolledStudents(LiveClass $liveClass, Course $course): void
    {
        try {
            $enrolledStudents = $course->enrollments()
                ->where('status', 'ACTIVE')
                ->with('user')
                ->get()
                ->pluck('user')
                ->filter();

            foreach ($enrolledStudents as $student) {
                $this->notificationService->send(
                    user: $student,
                    title: "📡 حصة مباشرة جديدة: {$liveClass->title_ar}",
                    message: "تمت جدولة حصة مباشرة جديدة في دورة {$course->title_ar} بتاريخ {$liveClass->scheduled_start_time->format('Y-m-d الساعة H:i')} على منصة {$liveClass->provider}.",
                    actionUrl: route('live-class.show', $liveClass->id),
                    actionText: 'تفاصيل الحصة المباشرة',
                    channels: ['database']
                );
            }
        } catch (\Throwable $e) {
            // Notification failure must not block the scheduling workflow
            logger()->error('LiveClass notification failed: ' . $e->getMessage());
        }
    }
}
