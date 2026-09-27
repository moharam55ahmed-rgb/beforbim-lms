<?php

namespace App\Modules\CourseAnnouncement\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Models\Course;
use App\Modules\CourseAnnouncement\Models\CourseAnnouncement;
use App\Modules\Notification\Services\NotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CourseAnnouncementService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Create and broadcast an announcement for a course.
     */
    public function createAnnouncement(Course $course, User $instructor, string $title, string $content): CourseAnnouncement
    {
        // Verify instructor owns the course or is admin
        if ($course->instructor_id !== $instructor->id && ! $instructor->hasAnyRole(['admin', 'super_admin'])) {
            throw ValidationException::withMessages([
                'course' => ['لا يمكنك نشر إعلانات إلا في الدورات التي تشرف عليها.'],
            ]);
        }

        $announcement = CourseAnnouncement::create([
            'course_id' => $course->id,
            'instructor_id' => $instructor->id,
            'title' => $title,
            'content' => $content,
            'published_at' => now(),
        ]);

        // Notify active enrolled students
        $course->load('enrollments.user');
        foreach ($course->enrollments as $enrollment) {
            if ($enrollment->status === 'ACTIVE' && $enrollment->user) {
                $this->notificationService->send(
                    user: $enrollment->user,
                    title: 'إعلان جديد في دورة: '.$course->title_ar,
                    message: $title,
                    actionUrl: route('learn.player', ['course' => $course->slug]),
                    actionText: 'عرض الدورة',
                    channels: ['database']
                );
            }
        }

        AuditLog::log(
            module: 'Course',
            action: 'ANNOUNCEMENT_PUBLISHED',
            actor: $instructor,
            target: $announcement,
            newValues: ['title' => $title, 'course_id' => $course->id],
            reason: 'Instructor published announcement to enrolled cohort'
        );

        return $announcement;
    }

    /**
     * Get paginated published announcements for a course.
     */
    public function getCourseAnnouncements(Course $course, int $perPage = 10): LengthAwarePaginator
    {
        return CourseAnnouncement::where('course_id', $course->id)
            ->with('instructor')
            ->published()
            ->latest('published_at')
            ->paginate($perPage);
    }
}
