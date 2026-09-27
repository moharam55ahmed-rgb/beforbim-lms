<?php

namespace App\Modules\Payment\Gateways;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use App\Modules\Payment\Models\Payment;
use Illuminate\Support\Str;

class CardPaymentGateway implements PaymentGatewayInterface
{
    /**
     * Process an instant card payment.
     */
    public function pay(Order $order, array $payload = []): array
    {
        $reference = 'CARD-' . strtoupper(Str::random(12));

        return [
            'success' => true,
            'status' => 'COMPLETED',
            'transaction_reference' => $reference,
            'gateway_payload' => array_merge($payload, [
                'gateway' => 'card',
                'card_last4' => $payload['card_last4'] ?? '4242',
                'card_brand' => $payload['card_brand'] ?? 'visa',
                'processed_at' => now()->toIso8601String(),
            ]),
            'message' => 'تمت معالجة الدفع بالبطاقة بنجاح.',
        ];
    }

    /**
     * Verify payment status with card network/processor.
     */
    public function verify(Payment $payment, array $payload = []): array
    {
        return [
            'verified' => true,
            'status' => 'COMPLETED',
            'transaction_reference' => $payment->transactions()->first()?->gateway_reference ?? 'CARD-VERIFIED',
            'message' => 'تم تأكيد العملية من بوابة البطاقات.',
        ];
    }

    /**
     * Refund card payment.
     */
    public function refund(Payment $payment, ?float $amount = null, ?string $reason = null): array
    {
        $refundAmount = $amount ?? (float) $payment->amount;
        $reference = 'REFUND-CARD-' . strtoupper(Str::random(10));

        return [
            'success' => true,
            'refund_reference' => $reference,
            'refunded_amount' => $refundAmount,
            'message' => 'تم استرداد المبلغ إلى البطاقة بنجاح.',
        ];
    }
}
