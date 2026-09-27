<?php

namespace App\Modules\CourseAnnouncement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\CourseAnnouncement\Services\CourseAnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseAnnouncementController extends Controller
{
    public function __construct(
        protected CourseAnnouncementService $announcementService
    ) {}

    /**
     * Display course announcements.
     */
    public function index(Course $course): View
    {
        $announcements = $this->announcementService->getCourseAnnouncements($course);

        return view('courses.announcements', [
            'course' => $course,
            'announcements' => $announcements,
        ]);
    }

    /**
     * Store new instructor announcement.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
        ]);

        $this->announcementService->createAnnouncement(
            course: $course,
            instructor: $request->user(),
            title: $validated['title'],
            content: $validated['content']
        );

        return back()->with('status', 'تم نشر الإعلان وإشعار جميع الطلاب المسجلين بالدورة بنجاح.');
    }
}
