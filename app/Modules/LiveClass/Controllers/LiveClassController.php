<?php

namespace App\Modules\LiveClass\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\LiveClass\Models\LiveClass;
use App\Modules\LiveClass\Services\LiveClassService;
use Illuminate\Http\Request;

class LiveClassController extends Controller
{
    public function __construct(
        protected LiveClassService $liveClassService
    ) {}

    /**
     * List all live classes for a course.
     */
    public function index(Course $course)
    {
        $user = auth()->user();

        $liveClasses = LiveClass::where('course_id', $course->id)
            ->orderBy('scheduled_start_time', 'desc')
            ->get();

        $upcoming = $this->liveClassService->getUpcomingForCourse($course);

        return view('live-class.index', compact('course', 'liveClasses', 'upcoming', 'user'));
    }

    /**
     * Schedule a new live class (Instructor only).
     */
    public function store(Request $request, Course $course)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'provider' => 'required|in:ZOOM,GOOGLE_MEET',
            'scheduled_start_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
        ]);

        $liveClass = $this->liveClassService->scheduleLiveClass($course, $user, $validated);

        return redirect()
            ->route('live-class.show', $liveClass->id)
            ->with('success', 'تم جدولة الحصة المباشرة بنجاح وإشعار الطلاب المشتركين.');
    }

    /**
     * Show live class detail page.
     */
    public function show(LiveClass $liveClass)
    {
        $user = auth()->user();
        $course = $liveClass->course;

        $attendeesCount = $liveClass->attendees()->count();
        $userAttendance = $user ? LiveClass::find($liveClass->id)?->attendees()->where('user_id', $user->id)->first() : null;

        return view('live-class.show', compact('liveClass', 'course', 'user', 'attendeesCount', 'userAttendance'));
    }

    /**
     * Redirect student to the live meeting room (join URL).
     */
    public function join(LiveClass $liveClass)
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $joinUrl = $this->liveClassService->authorizeStudentJoin($liveClass, $user);

        return redirect()->away($joinUrl);
    }

    /**
     * Record a student leaving the live class.
     */
    public function leave(LiveClass $liveClass)
    {
        $user = auth()->user();

        if ($user) {
            $this->liveClassService->recordStudentLeave($liveClass, $user);
        }

        return redirect()->route('learn.player', $liveClass->course?->slug)
            ->with('info', 'تم تسجيل حضورك في الحصة المباشرة بنجاح.');
    }

    /**
     * Mark live class as completed (Instructor only).
     */
    public function complete(Request $request, LiveClass $liveClass)
    {
        $user = auth()->user();
        $recordingUrl = $request->input('recording_url');

        $this->liveClassService->completeLiveClass($liveClass, $user, $recordingUrl);

        return redirect()->route('live-class.show', $liveClass->id)
            ->with('success', 'تم إغلاق الحصة المباشرة وتسجيلها كمكتملة.');
    }

    /**
     * Cancel a scheduled live class (Instructor only).
     */
    public function cancel(LiveClass $liveClass)
    {
        $user = auth()->user();

        $this->liveClassService->cancelLiveClass($liveClass, $user);

        return redirect()->back()->with('warning', 'تم إلغاء الحصة المباشرة المجدولة.');
    }
}
