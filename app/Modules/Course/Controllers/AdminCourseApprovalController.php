<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCourseApprovalController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    /**
     * Display all courses pending administrative audit.
     */
    public function pending(): View
    {
        $courses = Course::where('status', 'SUBMITTED')
            ->with(['instructor', 'category', 'sections.lessons'])
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.courses.pending', compact('courses'));
    }

    /**
     * Show detailed course content for academic audit.
     */
    public function show(Course $course): View
    {
        $course->load([
            'instructor.instructorProfile',
            'category',
            'sections.lessons.resources',
            'requirements',
        ]);

        return view('admin.courses.review', compact('course'));
    }

    /**
     * Approve and publish the course.
     */
    public function approve(Request $request, Course $course): RedirectResponse
    {
        $this->courseService->approveCourse($course, $request->user());

        return redirect()->route('admin.courses.pending')
            ->with('success', "تم اعتماد ونشر الدورة '{$course->title_ar}' بنجاح.");
    }

    /**
     * Reject course with feedback.
     */
    public function reject(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_feedback' => ['required', 'string', 'min:5'],
        ]);

        $this->courseService->rejectCourse($course, $validated['rejection_feedback'], $request->user());

        return redirect()->route('admin.courses.pending')
            ->with('success', "تم إرجاع الدورة '{$course->title_ar}' للمدرب مع الملاحظات الهندسية.");
    }
}
