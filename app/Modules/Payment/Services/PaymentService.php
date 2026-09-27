<?php

namespace App\Modules\Payment\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Enrollment\Services\EnrollmentService;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Events\PaymentFailed;
use App\Modules\Payment\Events\PaymentRefunded;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        protected EnrollmentService $enrollmentService,
        protected PaymentGatewayManager $gatewayManager = new PaymentGatewayManager()
    ) {}

    /**
     * Resolve a payment gateway instance.
     */
    public function gateway(?string $gatewayName = null): PaymentGatewayInterface
    {
        return $this->gatewayManager->gateway($gatewayName);
    }

    /**
     * Create an initial payment attempt for an order.
     */
    public function createPayment(Order $order, string $method, string $gateway): Payment
    {
        return Payment::create([
            'uuid' => (string) Str::uuid(),
            'order_id' => $order->id,
            'payment_method' => $method,
            'gateway' => $gateway,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'status' => 'PENDING',
        ]);
    }

    /**
     * Process order checkout via gateway abstraction.
     */
    public function processOrderPayment(Order $order, string $method, string $gatewayName, array $payload = []): Payment
    {
        $gateway = $this->gateway($gatewayName);
        $result = $gateway->pay($order, $payload);

        if (($result['status'] ?? '') === 'COMPLETED') {
            $payment = $this->createPayment($order, $method, $gatewayName);
            return $this->processDirectPaymentSuccess(
                $payment,
                $result['transaction_reference'] ?? 'TXN-' . Str::random(10),
                $result['gateway_payload'] ?? []
            );
        }

        if (($result['status'] ?? '') === 'REQUIRES_ADMIN_VERIFICATION') {
            return $this->submitBankTransferReceipt(
                $order,
                $payload['receipt_url'] ?? '',
                $payload['notes'] ?? null
            );
        }

        // Default pending payment record
        $payment = $this->createPayment($order, $method, $gatewayName);
        $payment->update([
            'status' => $result['status'] ?? 'PENDING',
            'gateway_payload' => $result['gateway_payload'] ?? [],
        ]);

        return $payment;
    }

    /**
     * Complete a payment immediately (e.g. simulated gateway, credit card, Apple Pay).
     * Automatically triggers enrollment activation upon verified success.
     */
    public function processDirectPaymentSuccess(Payment $payment, string $transactionRef, array $gatewayPayload = []): Payment
    {
        return DB::transaction(function () use ($payment, $transactionRef, $gatewayPayload) {
            $payment->update([
                'status' => 'COMPLETED',
                'gateway_payload' => $gatewayPayload,
            ]);

            // Create Transaction record
            $payment->transactions()->create([
                'uuid' => (string) Str::uuid(),
                'transaction_type' => 'CHARGE',
                'gateway_reference' => $transactionRef,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'fee_amount' => 0.00,
                'net_amount' => $payment->amount,
                'settled_at' => now(),
            ]);

            // Update order status
            $order = $payment->order;
            $order->update(['status' => 'COMPLETED']);

            AuditLog::log(
                'Payment',
                'PAYMENT_SUCCESS',
                $order->user,
                $payment,
                null,
                ['amount' => $payment->amount, 'ref' => $transactionRef]
            );

            // Activate course enrollments strictly for purchased items
            $this->enrollmentService->activateEnrollmentForOrder($order);

            // Fire PaymentCompleted domain event
            event(new PaymentCompleted($payment, ['transaction_reference' => $transactionRef]));

            return $payment;
        });
    }

    /**
     * Submit manual bank transfer receipt requiring administrative verification.
     */
    public function submitBankTransferReceipt(Order $order, string $receiptUrl, ?string $notes = null): Payment
    {
        return DB::transaction(function () use ($order, $receiptUrl, $notes) {
            $payment = Payment::create([
                'uuid' => (string) Str::uuid(),
                'order_id' => $order->id,
                'payment_method' => 'BANK_TRANSFER',
                'gateway' => 'manual_bank',
                'amount' => $order->total_amount,
                'currency' => $order->currency,
                'status' => 'REQUIRES_ADMIN_VERIFICATION',
                'receipt_attachment_url' => $receiptUrl,
                'verification_notes' => $notes,
            ]);

            $order->update(['status' => 'PROCESSING']);

            AuditLog::log(
                'Payment',
                'BANK_RECEIPT_SUBMITTED',
                $order->user,
                $payment,
                null,
                ['receipt_url' => $receiptUrl, 'amount' => $order->total_amount]
            );

            return $payment;
        });
    }

    /**
     * Admin approves and verifies manual payment receipt, triggering enrollment activation.
     */
    public function adminVerifyPayment(Payment $payment, User $admin, ?string $notes = null): Payment
    {
        return DB::transaction(function () use ($payment, $admin, $notes) {
            $gateway = $this->gateway($payment->gateway ?: 'bank_transfer');
            $verifyResult = $gateway->verify($payment, ['notes' => $notes, 'admin_id' => $admin->id]);
            $reference = $verifyResult['transaction_reference'] ?? ('MANUAL-BANK-' . strtoupper(Str::random(8)));

            $payment->update([
                'status' => 'COMPLETED',
                'verified_by_user_id' => $admin->id,
                'verified_at' => now(),
                'verification_notes' => $notes ?: $payment->verification_notes,
            ]);

            // Record settled transaction
            $payment->transactions()->create([
                'uuid' => (string) Str::uuid(),
                'transaction_type' => 'CHARGE',
                'gateway_reference' => $reference,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'fee_amount' => 0.00,
                'net_amount' => $payment->amount,
                'settled_at' => now(),
            ]);

            $order = $payment->order;
            $order->update(['status' => 'COMPLETED']);

            AuditLog::log(
                'Payment',
                'PAYMENT_VERIFIED_BY_ADMIN',
                $admin,
                $payment,
                null,
                ['verified_by' => $admin->id, 'amount' => $payment->amount]
            );

            // Activate course enrollments
            $this->enrollmentService->activateEnrollmentForOrder($order);

            // Fire PaymentCompleted domain event
            event(new PaymentCompleted($payment, ['verified_by' => $admin->id]));

            return $payment;
        });
    }

    /**
     * Admin rejects manual payment receipt.
     */
    public function adminRejectPayment(Payment $payment, User $admin, string $reason): Payment
    {
        return DB::transaction(function () use ($payment, $admin, $reason) {
            $payment->update([
                'status' => 'FAILED',
                'verified_by_user_id' => $admin->id,
                'verified_at' => now(),
                'verification_notes' => 'مرفوض: ' . $reason,
            ]);

            $payment->order->update(['status' => 'FAILED']);

            AuditLog::log(
                'Payment',
                'PAYMENT_REJECTED_BY_ADMIN',
                $admin,
                $payment,
                null,
                ['reason' => $reason]
            );

            // Fire PaymentFailed domain event
            event(new PaymentFailed($payment, $reason));

            return $payment;
        });
    }

    /**
     * Process refund for a payment and revoke associated enrollments.
     */
    public function refundPayment(Payment $payment, ?float $amount = null, ?string $reason = null, ?User $admin = null): Payment
    {
        return DB::transaction(function () use ($payment, $amount, $reason, $admin) {
            $refundAmount = $amount ?? (float) $payment->amount;
            $gateway = $this->gateway($payment->gateway);
            $refundResult = $gateway->refund($payment, $refundAmount, $reason);

            $refundRef = $refundResult['refund_reference'] ?? ('REFUND-' . strtoupper(Str::random(10)));

            // Record REFUND transaction
            $payment->transactions()->create([
                'uuid' => (string) Str::uuid(),
                'transaction_type' => 'REFUND',
                'gateway_reference' => $refundRef,
                'amount' => -$refundAmount,
                'currency' => $payment->currency,
                'fee_amount' => 0.00,
                'net_amount' => -$refundAmount,
                'settled_at' => now(),
            ]);

            $payment->update(['status' => 'REFUNDED']);
            $order = $payment->order;
            $order->update(['status' => 'REFUNDED']);

            // Revoke active enrollments originating from this order
            if ($admin) {
                $this->enrollmentService->revokeEnrollmentsForOrder($order, $admin, $reason ?: 'Payment refunded');
            }

            AuditLog::log(
                'Payment',
                'PAYMENT_REFUNDED',
                $admin ?? $order->user,
                $payment,
                null,
                ['refund_amount' => $refundAmount, 'reason' => $reason]
            );

            event(new PaymentRefunded($payment, $refundAmount, $reason));

            return $payment;
        });
    }
}
