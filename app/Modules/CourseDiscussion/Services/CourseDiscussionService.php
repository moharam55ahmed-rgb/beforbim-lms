<?php

namespace App\Modules\CourseDiscussion\Services;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\CourseDiscussion\Models\CourseDiscussion;
use App\Modules\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class CourseDiscussionService
{
    public function __construct(
        protected CourseAccessService $accessService = new CourseAccessService()
    ) {}

    /**
     * Get root questions and their replies for a course, optionally filtered by lesson.
     */
    public function getDiscussions(Course $course, ?int $lessonId = null): Collection
    {
        $query = CourseDiscussion::where('course_id', $course->id)
            ->whereNull('parent_id')
            ->where('status', 'active')
            ->with(['user', 'replies.user', 'lesson'])
            ->orderByDesc('created_at');

        if ($lessonId) {
            $query->where('lesson_id', $lessonId);
        }

        return $query->get();
    }

    /**
     * Post a new discussion question on a course or lesson.
     */
    public function postQuestion(User $user, Course $course, string $message, ?Lesson $lesson = null): CourseDiscussion
    {
        if (! $this->accessService->canParticipateInDiscussion($user, $course)) {
            throw new InvalidArgumentException('المشاركة في النقاشات مخصصة للمشتركين والمدربين فقط.');
        }

        $trimmed = trim($message);
        if (empty($trimmed)) {
            throw new InvalidArgumentException('نص السؤال لا يمكن أن يكون فارغاً.');
        }

        return CourseDiscussion::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'lesson_id' => $lesson?->id,
            'parent_id' => null,
            'message' => $trimmed,
            'status' => 'active',
        ]);
    }

    /**
     * Post a reply to an existing discussion thread.
     */
    public function postReply(User $user, CourseDiscussion $parent, string $message): CourseDiscussion
    {
        $course = $parent->course;

        if (! $this->accessService->canParticipateInDiscussion($user, $course)) {
            throw new InvalidArgumentException('المشاركة في النقاشات مخصصة للمشتركين والمدربين فقط.');
        }

        $trimmed = trim($message);
        if (empty($trimmed)) {
            throw new InvalidArgumentException('نص الرد لا يمكن أن يكون فارغاً.');
        }

        return CourseDiscussion::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'lesson_id' => $parent->lesson_id,
            'parent_id' => $parent->id,
            'message' => $trimmed,
            'status' => 'active',
        ]);
    }
}
