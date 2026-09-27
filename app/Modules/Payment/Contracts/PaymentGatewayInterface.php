<?php

namespace App\Modules\Payment\Contracts;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Process an initial payment charge for an order.
     *
     * @return array [
     *               'success' => bool,
     *               'status' => string ('COMPLETED', 'REQUIRES_ADMIN_VERIFICATION', 'PENDING', 'FAILED'),
     *               'transaction_reference' => ?string,
     *               'gateway_payload' => array,
     *               'message' => ?string
     *               ]
     */
    public function pay(Order $order, array $payload = []): array;

    /**
     * Verify payment status or confirm incoming webhook / admin verification.
     *
     * @return array [
     *               'verified' => bool,
     *               'status' => string,
     *               'transaction_reference' => ?string,
     *               'message' => ?string
     *               ]
     */
    public function verify(Payment $payment, array $payload = []): array;

    /**
     * Process refund for a previously completed payment.
     *
     * @return array [
     *               'success' => bool,
     *               'refund_reference' => ?string,
     *               'refunded_amount' => float,
     *               'message' => ?string
     *               ]
     */
    public function refund(Payment $payment, ?float $amount = null, ?string $reason = null): array;
}
