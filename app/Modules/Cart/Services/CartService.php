<?php

namespace App\Modules\Cart\Services;

use App\Models\User;
use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Services\EnrollmentService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartService
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    /**
     * Retrieve or initialize cart for an authenticated user or guest session.
     */
    public function getOrCreateCart(?User $user, ?string $sessionId = null): Cart
    {
        if ($user) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['discount_amount' => 0.00]
            );

            // Merge guest cart if sessionId exists
            if ($sessionId) {
                $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
                if ($guestCart) {
                    foreach ($guestCart->items as $item) {
                        if (! $cart->items()->where('course_id', $item->course_id)->exists()) {
                            $item->update(['cart_id' => $cart->id]);
                        } else {
                            $item->delete();
                        }
                    }
                    $guestCart->delete();
                }
            }

            return $cart;
        }

        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['discount_amount' => 0.00]
        );
    }

    /**
     * Add a course to the shopping cart.
     */
    public function addItem(Cart $cart, Course $course): CartItem
    {
        // 1. Check if course is published and available
        if ($course->status !== 'APPROVED') {
            throw new RuntimeException('هذه الدورة ليست متاحة للشراء حالياً.');
        }

        // 2. Check if user is already enrolled
        if ($cart->user && $this->enrollmentService->hasActiveAccess($cart->user, $course)) {
            throw new RuntimeException('أنت مشترك بالفعل في هذه الدورة التدريبية.');
        }

        // 3. Check if already in cart
        $existing = $cart->items()->where('course_id', $course->id)->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($cart, $course) {
            return $cart->items()->create([
                'purchasable_type' => Course::class,
                'purchasable_id' => $course->id,
                'course_id' => $course->id,
                'unit_price' => $course->effective_price,
            ]);
        });
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(Cart $cart, int $itemId): bool
    {
        $item = $cart->items()->where('id', $itemId)->first();

        return $item ? (bool) $item->delete() : false;
    }

    /**
     * Clear all items in the cart.
     */
    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['discount_amount' => 0.00, 'coupon_code' => null]);
    }

    /**
     * Get computed cart totals and items.
     */
    public function getCartTotals(Cart $cart): array
    {
        $cart->loadMissing('items.course.instructor');

        $subtotal = (float) $cart->items->sum('unit_price');
        $discount = (float) $cart->discount_amount;
        $total = max(0, $subtotal - $discount);

        return [
            'cart' => $cart,
            'items' => $cart->items,
            'items_count' => $cart->items->count(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'currency' => $cart->items->first()?->course?->currency ?? 'USD',
        ];
    }
}
