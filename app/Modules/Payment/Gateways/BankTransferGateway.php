<?php

namespace App\Modules\Payment\Gateways;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use App\Modules\Payment\Models\Payment;
use Illuminate\Support\Str;

class BankTransferGateway implements PaymentGatewayInterface
{
    /**
     * Process bank transfer submission (awaiting receipt upload or admin confirmation).
     */
    public function pay(Order $order, array $payload = []): array
    {
        $reference = 'BANK-REQ-'.strtoupper(Str::random(10));

        return [
            'success' => true,
            'status' => 'REQUIRES_ADMIN_VERIFICATION',
            'transaction_reference' => $reference,
            'gateway_payload' => array_merge($payload, [
                'gateway' => 'manual_bank',
                'receipt_url' => $payload['receipt_url'] ?? null,
                'submitted_at' => now()->toIso8601String(),
            ]),
            'message' => 'تم استلام طلب التحويل البنكي وهو قيد مراجعة الإدارة.',
        ];
    }

    /**
     * Verify bank transfer (administrative verification).
     */
    public function verify(Payment $payment, array $payload = []): array
    {
        $reference = 'MANUAL-BANK-'.strtoupper(Str::random(8));

        return [
            'verified' => true,
            'status' => 'COMPLETED',
            'transaction_reference' => $reference,
            'message' => 'تم التحقق من التحويل البنكي وتأكيده.',
        ];
    }

    /**
     * Refund bank transfer payment.
     */
    public function refund(Payment $payment, ?float $amount = null, ?string $reason = null): array
    {
        $refundAmount = $amount ?? (float) $payment->amount;
        $reference = 'REFUND-BANK-'.strtoupper(Str::random(10));

        return [
            'success' => true,
            'refund_reference' => $reference,
            'refunded_amount' => $refundAmount,
            'message' => 'تم تسجيل استرداد الحوالة البنكية.',
        ];
    }
}
