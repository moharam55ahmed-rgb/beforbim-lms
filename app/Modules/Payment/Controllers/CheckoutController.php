<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Order\Services\OrderService;
use App\Modules\Payment\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Show the checkout page.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart($request->user());
        $totals = $this->cartService->getCartTotals($cart);

        if ($totals['items_count'] === 0) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة. يرجى اختيار دورة تدريبية أولاً.');
        }

        return view('checkout.index', $totals);
    }

    /**
     * Process checkout and handle payment method.
     */
    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:CARD,APPLE_PAY,BANK_TRANSFER'],
            'receipt_url' => ['required_if:payment_method,BANK_TRANSFER', 'nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $cart = $this->cartService->getOrCreateCart($user);

        try {
            // 1. Create Order
            $order = $this->orderService->createOrderFromCart($cart, $user, $validated['notes'] ?? null);

            // 2. Handle Payment Method
            if ($validated['payment_method'] === 'BANK_TRANSFER') {
                $this->paymentService->submitBankTransferReceipt(
                    $order,
                    $validated['receipt_url'],
                    $validated['notes'] ?? null
                );

                return redirect()->route('orders.show', $order->order_number)
                    ->with('success', 'تم استلام إيصال التحويل البنكي بنجاح. سيتم تفعيل اشتراكك فور مراجعة الإدارة المالية.');
            }

            // Direct electronic card payment simulation
            $payment = $this->paymentService->createPayment($order, $validated['payment_method'], 'stripe');
            $this->paymentService->processDirectPaymentSuccess(
                $payment,
                'TXN-' . strtoupper(Str::random(10)),
                ['provider' => 'Stripe Gateway Mock']
            );

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', 'تمت عملية الدفع بنجاح وتفعيل اشتراكك بالدورات الهندسية مباشرة!');

        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
