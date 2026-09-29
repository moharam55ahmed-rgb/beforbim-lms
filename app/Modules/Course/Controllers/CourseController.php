<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Requests\StoreCourseRequest;
use App\Modules\Course\Requests\UpdateCourseRequest;
use App\Modules\Course\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    /**
     * Display a listing of courses.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user && $user->hasRole(['super_admin', 'admin'])) {
            $courses = Course::with(['instructor', 'category'])
                ->latest()
                ->paginate(15);
        } elseif ($user && $user->hasRole('instructor')) {
            $courses = Course::where('instructor_id', $user->id)
                ->with('category')
                ->latest()
                ->paginate(15);
        } else {
            $courses = Course::published()
                ->with(['instructor', 'category'])
                ->latest('published_at')
                ->paginate(12);
        }

        return view('courses.index', compact('courses'));
    }

    /**
     * Display the course details page.
     */
    public function show(Course $course): View
    {
        $course->load([
            'instructor.instructorProfile',
            'category',
            'sections.lessons',
            'requirements',
            'approvedReviews.student',
            'announcements' => function ($q) {
                $q->take(3);
            },
        ]);

        $relatedCourses = Course::where('status', 'APPROVED')
            ->where('id', '!=', $course->id)
            ->with(['instructor', 'category'])
            ->take(3)
            ->get();

        return view('courses.show', compact('course', 'relatedCourses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();

        return view('courses.create', compact('categories'));
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $course = $this->courseService->createCourse($request->validated(), $request->user());

        return redirect()->route('courses.curriculum', $course->id)
            ->with('success', 'تم إنشاء الدورة بنجاح كمسودة. يمكنك الآن بناء المنهج والأقسام.');
    }

    /**
     * Show the form for editing the course.
     */
    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        $categories = Category::where('is_active', true)->orderBy('display_order')->get();

        return view('courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $this->courseService->updateCourse($course, $request->validated());

        return redirect()->route('courses.edit', $course->id)
            ->with('success', 'تم تحديث بيانات الدورة بنجاح.');
    }

    /**
     * Submit a course for administrative review.
     */
    public function submit(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        if ($course->sections()->doesntHave('lessons')->exists() || $course->sections()->count() === 0) {
            return back()->with('error', 'يجب أن تحتوي الدورة على قسم واحد ودرس واحد على الأقل قبل تقديمها للاعتماد الأكاديمي.');
        }

        $this->courseService->submitForApproval($course);

        return back()->with('success', 'تم تقديم الدورة للمراجعة الأكاديمية بنجاح.');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        $this->courseService->deleteCourse($course);

        return redirect()->route('courses.index')
            ->with('success', 'تم حذف الدورة بنجاح.');
    }
}
