<?php

namespace App\Modules\Curriculum\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Curriculum\Requests\StoreLessonRequest;
use App\Modules\Curriculum\Requests\StoreResourceRequest;
use App\Modules\Curriculum\Requests\StoreSectionRequest;
use App\Modules\Curriculum\Services\CurriculumService;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurriculumController extends Controller
{
    public function __construct(
        protected CurriculumService $curriculumService
    ) {}

    /**
     * Show the Curriculum Builder interface for a course.
     */
    public function builder(Course $course): View
    {
        $this->authorize('update', $course);

        $course->load([
            'sections' => fn($q) => $q->orderBy('order_index')
                ->with(['lessons' => fn($lq) => $lq->orderBy('order_index')->with(['content', 'resources'])]),
        ]);

        return view('courses.curriculum', compact('course'));
    }

    /**
     * Store a new section.
     */
    public function storeSection(StoreSectionRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $this->curriculumService->addSection($course, $request->validated());

        return back()->with('success', 'تمت إضافة القسم بنجاح.');
    }

    /**
     * Update an existing section.
     */
    public function updateSection(StoreSectionRequest $request, CourseSection $section): RedirectResponse
    {
        $this->authorize('update', $section->course);

        $this->curriculumService->updateSection($section, $request->validated());

        return back()->with('success', 'تم تعديل بيانات القسم بنجاح.');
    }

    /**
     * Delete a section.
     */
    public function destroySection(CourseSection $section): RedirectResponse
    {
        $this->authorize('update', $section->course);

        $this->curriculumService->deleteSection($section);

        return back()->with('success', 'تم حذف القسم وجميع دروسه بنجاح.');
    }

    /**
     * Reorder sections.
     */
    public function reorderSections(Request $request, Course $course): JsonResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['integer', 'exists:course_sections,id'],
        ]);

        $this->curriculumService->reorderSections($course, $validated['ordered_ids']);

        return response()->json(['message' => 'تم حفظ ترتيب الأقسام بنجاح.']);
    }

    /**
     * Store a new lesson inside a section.
     */
    public function storeLesson(StoreLessonRequest $request, CourseSection $section): RedirectResponse
    {
        $this->authorize('update', $section->course);

        $this->curriculumService->addLesson($section, $request->validated());

        return back()->with('success', 'تمت إضافة المحاضرة/الدرس بنجاح.');
    }

    /**
     * Update an existing lesson.
     */
    public function updateLesson(StoreLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson->section->course);

        $this->curriculumService->updateLesson($lesson, $request->validated());

        return back()->with('success', 'تم تعديل بيانات الدرس بنجاح.');
    }

    /**
     * Delete a lesson.
     */
    public function destroyLesson(Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson->section->course);

        $this->curriculumService->deleteLesson($lesson);

        return back()->with('success', 'تم حذف الدرس ومحتوياته بنجاح.');
    }

    /**
     * Reorder lessons inside a section.
     */
    public function reorderLessons(Request $request, CourseSection $section): JsonResponse
    {
        $this->authorize('update', $section->course);

        $validated = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['integer', 'exists:lessons,id'],
        ]);

        $this->curriculumService->reorderLessons($section, $validated['ordered_ids']);

        return response()->json(['message' => 'تم حفظ ترتيب الدروس بنجاح.']);
    }

    /**
     * Attach a downloadable resource to a lesson.
     */
    public function storeResource(StoreResourceRequest $request, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson->section->course);

        $this->curriculumService->addResource($lesson, $request->validated());

        return back()->with('success', 'تم إرفاق الملف الهندسي بالدرس بنجاح.');
    }

    /**
     * Delete a downloadable resource.
     */
    public function destroyResource(LessonResource $resource): RedirectResponse
    {
        $this->authorize('update', $resource->lesson->section->course);

        $this->curriculumService->deleteResource($resource);

        return back()->with('success', 'تم حذف الملف المرفق بنجاح.');
    }
}
