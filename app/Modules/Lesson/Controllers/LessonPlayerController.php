<?php

namespace App\Modules\Lesson\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Services\CourseAccessService;
use App\Modules\CourseDiscussion\Services\CourseDiscussionService;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonContent;
use App\Modules\Media\Models\LessonResource;
use App\Modules\Media\Services\MediaSecurityService;
use App\Modules\Progress\Services\LessonProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class LessonPlayerController extends Controller
{
    public function __construct(
        protected CourseAccessService $accessService,
        protected LessonProgressService $progressService,
        protected MediaSecurityService $securityService,
        protected CourseDiscussionService $discussionService
    ) {}

    /**
     * Display the course player interface with lesson content, curriculum navigation, and community tools.
     */
    public function show(Course $course, ?Lesson $lesson = null)
    {
        $user = auth()->user();

        // 1. Resolve active lesson
        $activeLesson = $lesson;
        if (! $activeLesson) {
            $activeLesson = $course->lessons()->first();
        }

        if (! $activeLesson) {
            return view('learn.empty', compact('course'));
        }

        // 2. Validate lesson access permissions via CourseAccessService
        $this->accessService->authorizeLessonAccess($user, $activeLesson);

        // 3. Eager load curriculum hierarchy
        $sections = $course->sections()
            ->with(['lessons' => function ($q) {
                $q->orderBy('order_index');
            }, 'lessons.contents'])
            ->orderBy('order_index')
            ->get();

        // Flatten lessons for linear navigation (previous/next)
        $allLessons = $sections->flatMap->lessons;
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $activeLesson->id);
        $previousLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $allLessons->count() - 1 ? $allLessons->get($currentIndex + 1) : null;

        // 4. Progress and completion data
        $progressData = $user ? $this->progressService->getCourseProgressData($user, $course) : [
            'total_lessons' => $allLessons->count(),
            'completed_count' => 0,
            'completed_lesson_ids' => [],
            'percentage' => 0.0,
        ];

        $currentProgress = $user ? $this->progressService->getOrCreateProgress($user, $activeLesson) : null;

        // 5. Contents and media
        $contents = $activeLesson->contents()->where('visibility_status', '!=', 'hidden')->get();
        $resources = $activeLesson->resources()->get();

        // Generate signed URLs for resources and videos
        $signedResourceUrls = [];
        if ($user) {
            foreach ($resources as $resource) {
                if ($this->accessService->canDownloadResource($user, $resource)) {
                    $signedResourceUrls[$resource->id] = $this->securityService->generateSignedResourceDownloadUrl($resource, $user);
                }
            }
        }

        // 6. Community data: Announcements & Discussions
        $announcements = $course->announcements()->published()->get();
        $discussions = $this->discussionService->getDiscussions($course, $activeLesson->id);

        return view('learn.player', compact(
            'course',
            'activeLesson',
            'sections',
            'previousLesson',
            'nextLesson',
            'progressData',
            'currentProgress',
            'contents',
            'resources',
            'signedResourceUrls',
            'announcements',
            'discussions'
        ));
    }

    /**
     * Record playback time and position.
     */
    public function saveProgress(Request $request, Lesson $lesson)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $this->accessService->authorizeLessonAccess($user, $lesson);

        $validated = $request->validate([
            'position' => 'required|integer|min:0',
            'watch_seconds' => 'nullable|integer|min:0',
        ]);

        $this->progressService->recordPlayback(
            $user,
            $lesson,
            $validated['position'],
            $validated['watch_seconds'] ?? 0
        );

        return response()->json(['success' => true]);
    }

    /**
     * Toggle lesson completion status.
     */
    public function toggleComplete(Request $request, Lesson $lesson)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $this->accessService->authorizeLessonAccess($user, $lesson);

        $result = $this->progressService->toggleCompletion($user, $lesson);

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with('status', $result['is_completed'] ? 'تم تحديد الدرس كمكتمل بنجاح!' : 'تم إلغاء تحديد اكتمال الدرس.');
    }

    /**
     * Securely download lesson engineering resources.
     */
    public function downloadResource(Request $request, LessonResource $resource)
    {
        $user = auth()->user();

        if (! $this->accessService->canDownloadResource($user, $resource)) {
            abort(Response::HTTP_FORBIDDEN, 'غير مصرح بتحميل هذا الملف الهندسي. التحميل متاح حصرياً للمشتركين المسجلين في الدورة.');
        }

        // Check if physical file exists in storage
        if ($resource->file_path && Storage::disk('local')->exists($resource->file_path)) {
            return Storage::disk('local')->download($resource->file_path, $resource->file_name);
        }

        // Graceful stream/fallback download
        return response()->streamDownload(function () use ($resource) {
            echo 'Beforbim Engineering Resource: '.($resource->title_ar ?: $resource->title_en);
        }, $resource->file_name, [
            'Content-Type' => $resource->mime_type ?: 'application/octet-stream',
        ]);
    }

    /**
     * Secure video stream verification.
     */
    public function videoStream(Request $request, LessonContent $content)
    {
        $user = auth()->user();
        $token = $request->query('token');

        if (! $user || ! $token || ! $this->securityService->validateMediaToken($token, $content->id, $user->id)) {
            abort(Response::HTTP_FORBIDDEN, 'رابط الفيديو غير مصرح به أو منتهي الصلاحية.');
        }

        return response()->json([
            'stream_url' => $content->video_hls_url ?: 'https://video.beforbim.com/stream/'.$content->video_asset_id,
            'provider' => $content->video_provider ?: 'beforbim_secure_player',
        ]);
    }
}
