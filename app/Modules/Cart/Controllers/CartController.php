<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Course\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

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
        $cart = $this->cartService->getOrCreateCart($request->user(), session()->getId());
        $totals = $this->cartService->getCartTotals($cart);

        return view('cart.index', $totals);
    }

    /**
     * Add a course to the shopping cart.
     */
    public function add(Request $request, Course $course): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart($request->user(), session()->getId());

        try {
            $this->cartService->addItem($cart, $course);

            return redirect()->route('cart.index')->with('success', "تمت إضافة '{$course->title_ar}' إلى سلة المشتريات.");
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, int $itemId): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart($request->user(), session()->getId());
        $this->cartService->removeItem($cart, $itemId);

        return redirect()->route('cart.index')->with('success', 'تم حذف العنصر من السلة.');
    }
}
