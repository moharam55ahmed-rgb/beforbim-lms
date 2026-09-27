<?php

namespace App\Modules\Assessment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentAttempt;
use App\Modules\Assessment\Services\AssessmentService;
use App\Modules\Course\Models\Course;
use App\Modules\ExamSecurity\Services\ExamSecurityService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct(
        protected AssessmentService $assessmentService,
        protected ExamSecurityService $securityService
    ) {}

    /**
     * Show assessment overview, instructions, and past attempts.
     */
    public function show(Course $course, Assessment $assessment)
    {
        $user = auth()->user();
        $eligibility = $user ? $this->assessmentService->checkAttemptEligibility($user, $assessment) : ['allowed' => false];
        $pastAttempts = $user ? $assessment->attempts()->where('user_id', $user->id)->latest()->get() : collect();

        return view('assessment.show', compact('course', 'assessment', 'eligibility', 'pastAttempts'));
    }

    /**
     * Start or resume an assessment attempt.
     */
    public function start(Request $request, Course $course, Assessment $assessment)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $attempt = $this->assessmentService->startAttempt($user, $assessment);

        return redirect()->route('assessment.take', [$course->slug, $assessment->id, $attempt->id]);
    }

    /**
     * Display the active exam attempt interface with timer and questions.
     */
    public function take(Course $course, Assessment $assessment, AssessmentAttempt $attempt)
    {
        $user = auth()->user();
        if ($attempt->user_id !== $user->id || $attempt->status !== 'IN_PROGRESS') {
            return redirect()->route('assessment.result', $attempt->id);
        }

        $questions = $assessment->questions;

        return view('assessment.take', compact('course', 'assessment', 'attempt', 'questions'));
    }

    /**
     * Submit answers and finalize the attempt.
     */
    public function submit(Request $request, AssessmentAttempt $attempt)
    {
        $user = auth()->user();
        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $answers = $request->input('answers', []);
        $this->assessmentService->submitAttempt($attempt, $answers);

        return redirect()->route('assessment.result', $attempt->id);
    }

    /**
     * Log anti-cheat security event beacon.
     */
    public function logSecurityViolation(Request $request, AssessmentAttempt $attempt)
    {
        $validated = $request->validate([
            'event_type' => 'required|string',
            'details' => 'nullable|string',
        ]);

        $event = $this->securityService->logViolation(
            $attempt,
            $validated['event_type'],
            $request->ip(),
            $validated['details'] ?? null
        );

        return response()->json([
            'success' => true,
            'violations_count' => $attempt->fresh()->anti_cheat_violations_count,
            'is_disqualified' => $attempt->fresh()->status === 'DISQUALIFIED',
        ]);
    }

    /**
     * Display the attempt result and grade breakdown.
     */
    public function result(AssessmentAttempt $attempt)
    {
        $user = auth()->user();
        $assessment = $attempt->assessment;
        $course = $assessment->course;

        return view('assessment.result', compact('course', 'assessment', 'attempt'));
    }
}
