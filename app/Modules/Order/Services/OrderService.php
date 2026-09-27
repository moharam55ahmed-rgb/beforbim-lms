<?php

namespace App\Modules\Order\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Cart\Models\Cart;
use App\Modules\Course\Models\Course;
use App\Modules\Order\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    /**
     * Create an Order from a shopping cart.
     */
    public function createOrderFromCart(Cart $cart, User $user, ?string $notes = null): Order
    {
        if ($cart->items()->count() === 0) {
            throw new RuntimeException('لا يمكن إنشاء طلب شراء من سلة فارغة.');
        }

        return DB::transaction(function () use ($cart, $user, $notes) {
            $cart->loadMissing('items.course');

            $subtotal = (float) $cart->items->sum('unit_price');
            $discount = (float) $cart->discount_amount;
            $total = max(0, $subtotal - $discount);
            $currency = $cart->items->first()?->course?->currency ?? 'USD';

            $order = Order::create([
                'order_number' => 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => 0.00,
                'total_amount' => $total,
                'currency' => $currency,
                'coupon_code' => $cart->coupon_code,
                'status' => 'PENDING',
                'notes' => $notes,
            ]);

            foreach ($cart->items as $cartItem) {
                $course = $cartItem->course;

                $order->items()->create([
                    'purchasable_type' => Course::class,
                    'purchasable_id' => $course->id,
                    'course_id' => $course->id,
                    'title_snapshot' => $course->title_ar,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->unit_price,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->update(['discount_amount' => 0.00, 'coupon_code' => null]);

            AuditLog::log(
                'Order',
                'ORDER_CREATED',
                $user,
                $order,
                null,
                ['order_number' => $order->order_number, 'total' => $order->total_amount]
            );

            return $order;
        });
    }

    /**
     * Get paginated purchase history for a user.
     */
    public function getUserOrders(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->with(['items.course', 'payments'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find order belonging to user.
     */
    public function getOrderForUser(int|string $orderId, User $user): Order
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->where(function ($q) use ($orderId) {
                if (is_numeric($orderId)) {
                    $q->where('id', $orderId)->orWhere('order_number', (string) $orderId);
                } else {
                    $q->where('order_number', $orderId);
                }
            })
            ->with(['items.course.instructor', 'payments.transactions'])
            ->firstOrFail();
    }
}
