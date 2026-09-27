<?php

namespace App\Modules\CourseDiscussion\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\CourseDiscussion\Models\CourseDiscussion;
use App\Modules\CourseDiscussion\Services\CourseDiscussionService;
use App\Modules\Lesson\Models\Lesson;
use Illuminate\Http\Request;

class CourseDiscussionController extends Controller
{
    public function __construct(
        protected CourseDiscussionService $discussionService
    ) {}

    /**
     * Store a question or a reply in course discussions.
     */
    public function store(Request $request, Course $course)
    {
        $user = auth()->user();
        if (! $user) {
            return back()->with('error', 'يجب تسجيل الدخول للمشاركة في النقاشات.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:3|max:3000',
            'lesson_id' => 'nullable|exists:lessons,id',
            'parent_id' => 'nullable|exists:course_discussions,id',
        ]);

        $lesson = ! empty($validated['lesson_id']) ? Lesson::find($validated['lesson_id']) : null;

        if (! empty($validated['parent_id'])) {
            $parent = CourseDiscussion::findOrFail($validated['parent_id']);
            $this->discussionService->postReply($user, $parent, $validated['message']);
            $message = 'تمت إضافة ردك بنجاح!';
        } else {
            $this->discussionService->postQuestion($user, $course, $validated['message'], $lesson);
            $message = 'تم طرح سؤالك بنجاح! سيقوم المدرب أو الزملاء بالرد قريباً.';
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('status', $message);
    }
}
