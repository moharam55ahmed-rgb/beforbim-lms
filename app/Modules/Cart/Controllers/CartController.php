<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Course\Controllers\CourseController;
use App\Modules\Course\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Show the shopping cart contents.
     */
    public function index(Request $request): View
    {
        try {
            $cart = $this->cartService->getOrCreateCart($request->user(), session()->getId());
            $totals = $this->cartService->getCartTotals($cart);

            $view = view('cart.index', $totals);
            $view->render();

            return $view;
        } catch (\Throwable) {
            return view('cart.index', $this->getSessionCartTotals());
        }
    }

    /**
     * Add a course to the shopping cart.
     */
    public function add(Request $request, mixed $course): RedirectResponse
    {
        $courseModel = null;
        if ($course instanceof Course) {
            $courseModel = $course;
        } else {
            try {
                $courseModel = Course::find($course);
            } catch (\Throwable) {
                $courseModel = null;
            }
        }

        if (! $courseModel) {
            $courseModel = app(CourseController::class)->getFallbackCourseModel($course);
        }

        if (! $courseModel) {
            return redirect()->route('courses.index')->with('error', 'الدورة غير موجودة.');
        }

        try {
            $cart = $this->cartService->getOrCreateCart($request->user(), session()->getId());
            $this->cartService->addItem($cart, $courseModel);
        } catch (\Throwable) {
            $this->addItemToSessionCart($courseModel);
        }

        $title = $courseModel->title_ar ?: ($courseModel->title_en ?: $courseModel->title);

        return redirect()->route('cart.index')->with('success', "تمت إضافة '{$title}' إلى سلة المشتريات.");
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, mixed $itemId): RedirectResponse
    {
        try {
            $cart = $this->cartService->getOrCreateCart($request->user(), session()->getId());
            $this->cartService->removeItem($cart, (int) $itemId);
        } catch (\Throwable) {
            $this->removeItemFromSessionCart($itemId);
        }

        return redirect()->route('cart.index')->with('success', 'تم حذف العنصر من السلة.');
    }

    /**
     * Store item in guest session cart when DB is unavailable.
     */
    protected function addItemToSessionCart(Course $course): void
    {
        $ids = session()->get('guest_cart_course_ids', []);
        $courseId = (int) $course->id;
        if (! in_array($courseId, $ids, true)) {
            $ids[] = $courseId;
        }
        session()->put('guest_cart_course_ids', $ids);
    }

    /**
     * Remove item from guest session cart.
     */
    protected function removeItemFromSessionCart(mixed $itemId): void
    {
        $ids = session()->get('guest_cart_course_ids', []);
        $ids = array_values(array_filter($ids, fn ($id) => (int) $id !== (int) $itemId));
        session()->put('guest_cart_course_ids', $ids);
    }

    /**
     * Calculate cart totals from guest session.
     *
     * @return array<string, mixed>
     */
    protected function getSessionCartTotals(): array
    {
        $ids = session()->get('guest_cart_course_ids', []);
        $courseController = app(CourseController::class);
        $items = collect();
        $subtotal = 0.0;

        foreach ($ids as $id) {
            $course = $courseController->getFallbackCourseModel($id);
            if ($course) {
                $price = (float) ($course->sale_price ?? $course->price ?? 899.00);
                $subtotal += $price;

                $itemObj = new \stdClass;
                $itemObj->id = (int) $course->id;
                $itemObj->course = $course;
                $itemObj->unit_price = $price;
                $items->push($itemObj);
            }
        }

        return [
            'cart' => null,
            'items' => $items,
            'items_count' => $items->count(),
            'subtotal' => $subtotal,
            'discount' => 0.0,
            'total' => $subtotal,
        ];
    }
}
