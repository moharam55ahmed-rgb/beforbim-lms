<?php

namespace App\Modules\Notification\Services;

use App\Models\User;
use App\Modules\Assignment\Models\AssignmentSubmission;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Notification\Events\AssignmentGraded;
use App\Modules\Notification\Events\CoursePublished;
use App\Modules\Notification\Events\EnrollmentApproved;
use App\Modules\Notification\Events\UserRegistered;
use App\Modules\Notification\Notifications\BeforbimGeneralNotification;
use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Dispatch notification to a user with configurable channels.
     */
    public function send(
        User $user,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionText = null,
        array $channels = ['database'],
        array $extraData = []
    ): void {
        $notification = new BeforbimGeneralNotification(
            title: $title,
            message: $message,
            actionUrl: $actionUrl,
            actionText: $actionText,
            extraData: $extraData,
            channels: $channels
        );

        $user->notify($notification);
    }

    /**
     * Handle UserRegistered domain event.
     */
    public function handleUserRegistered(UserRegistered $event): void
    {
        $user = $event->user;
        $this->send(
            user: $user,
            title: 'مرحباً بك في منصة Beforbim!',
            message: 'أهلاً بك في منصة نمذجة معلومات البناء BIM الأولى للمهندسين. استكشف الدورات الهندسية وابدأ رحلتك الاحترافية.',
            actionUrl: route('student.dashboard'),
            actionText: 'الانتقال إلى لوحة التحكم',
            channels: ['database']
        );
    }

    /**
     * Handle EnrollmentApproved domain event.
     */
    public function handleEnrollmentApproved(EnrollmentApproved $event): void
    {
        $enrollment = $event->enrollment;
        $user = $enrollment->user;
        $course = $enrollment->course;

        if ($user && $course) {
            $this->send(
                user: $user,
                title: "تم تفعيل اشتراكك في دورة: {$course->title_ar}",
                message: 'أصبح بإمكانك الآن الدخول إلى مشغل الدورة ومتابعة كافة الدروس وتحميل الملفات الهندسية.',
                actionUrl: route('learn.player', $course->slug),
                actionText: 'بدء التعلم الآن',
                channels: ['database']
            );
        }
    }

    /**
     * Handle CoursePublished domain event.
     */
    public function handleCoursePublished(CoursePublished $event): void
    {
        $course = $event->course;
        $instructor = $course->instructor;

        if ($instructor) {
            $this->send(
                user: $instructor,
                title: "تم اعتماد ونشر دورتك: {$course->title_ar}",
                message: 'تهانينا! تمت مراجعة واعتماد دورتك التدريبية بنجاح وأصبحت متاحة الآن للتسجيل والاشتراك للطلاب.',
                actionUrl: route('instructor.dashboard'),
                actionText: 'عرض الدورة في الاستوديو',
                channels: ['database']
            );
        }
    }

    /**
     * Handle AssignmentGraded domain event.
     */
    public function handleAssignmentGraded(AssignmentGraded $event): void
    {
        $submission = $event->submission;
        $user = $submission->user;
        $assignment = $submission->assignment;

        if ($user && $assignment) {
            $this->send(
                user: $user,
                title: "تم تصحيح الواجب الهندسي: {$assignment->title_ar}",
                message: "حصلت على درجة {$submission->grade} من {$assignment->total_points}. تفقد ملاحظات وتقييم المدرب.",
                actionUrl: route('student.dashboard'),
                actionText: 'عرض التقييم والملاحظات',
                channels: ['database']
            );
        }
    }

    /**
     * Handle PaymentCompleted domain event.
     */
    public function handlePaymentCompleted(PaymentCompleted $event): void
    {
        $payment = $event->payment;
        $order = $payment->order;
        $user = $order?->user;

        if ($user && $order) {
            $this->send(
                user: $user,
                title: "تم استلام وسداد دفعتك المالية بنجاح للطلب #{$order->order_number}",
                message: "تم تأكيد سداد مبلغ {$payment->amount} {$payment->currency} بنجاح عبر بوابة الدفع وتم تفعيل الدورات المطلوبة.",
                actionUrl: route('student.dashboard'),
                actionText: 'عرض الدورات المفعّلة',
                channels: ['database']
            );
        }
    }

    /**
     * Fetch unread or all notifications for a user.
     */
    public function getUserNotifications(User $user, int $limit = 15): Collection
    {
        return $user->notifications()->take($limit)->get();
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Notify a student about a newly scheduled live engineering class.
     */
    public function sendLiveClassScheduled(User $student, string $classTitle, string $courseName, string $startTime, string $liveClassUrl): void
    {
        $this->send(
            user: $student,
            title: "📡 حصة مباشرة جديدة: {$classTitle}",
            message: "تمت جدولة حصة مباشرة في دورة {$courseName} بتاريخ {$startTime}. لا تفوت الحصة التفاعلية مع المدرب الهندسي.",
            actionUrl: $liveClassUrl,
            actionText: 'تفاصيل وانضمام للحصة المباشرة',
            channels: ['database']
        );
    }
}

