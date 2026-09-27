<?php

namespace App\Modules\CourseReview\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\CourseReview\Services\CourseReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseReviewController extends Controller
{
    public function __construct(
        protected CourseReviewService $reviewService
    ) {}

    /**
     * Submit a review for a course.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $this->reviewService->submitReview(
            student: $request->user(),
            course: $course,
            rating: (int) $validated['rating'],
            reviewText: $validated['review_text'] ?? null
        );

        return back()->with('status', 'شكراً لك! تم استلام تقييمك للدورة وهو قيد المراجعة والاعتماد.');
    }

    /**
     * Admin view for moderating course reviews.
     */
    public function adminIndex(Request $request): View
    {
        $status = $request->query('status');
        $reviews = $this->reviewService->getAllReviewsFiltered($status);

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Admin approve or reject a review.
     */
    public function moderate(Request $request, CourseReview $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_feedback' => 'nullable|string|max:500',
        ]);

        $this->reviewService->moderateReview(
            review: $review,
            status: $validated['status'],
            adminFeedback: $validated['admin_feedback'] ?? null,
            admin: $request->user()
        );

        return back()->with('status', 'تم حفظ قرار تدقيق التقييم بنجاح.');
    }
}
