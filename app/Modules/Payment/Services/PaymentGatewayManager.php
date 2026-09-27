<?php

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use App\Modules\Payment\Gateways\BankTransferGateway;
use App\Modules\Payment\Gateways\CardPaymentGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    /**
     * @var array<string, PaymentGatewayInterface>
     */
    protected array $gateways = [];

    public function __construct()
    {
        $this->registerDefaultGateways();
    }

    protected function registerDefaultGateways(): void
    {
        $cardGateway = new CardPaymentGateway();
        $this->register('card', $cardGateway);
        $this->register('credit_card', $cardGateway);
        $this->register('simulated_card', $cardGateway);

        $bankGateway = new BankTransferGateway();
        $this->register('bank_transfer', $bankGateway);
        $this->register('manual_bank', $bankGateway);
    }

    public function register(string $name, PaymentGatewayInterface $gateway): self
    {
        $this->gateways[strtolower($name)] = $gateway;
        return $this;
    }

    public function gateway(?string $name = null): PaymentGatewayInterface
    {
        $gatewayName = strtolower($name ?: 'card');

        if (! isset($this->gateways[$gatewayName])) {
            // Default fallback to CardPaymentGateway for extensible unknown card gateways
            return $this->gateways['card'];
        }

        return $this->gateways[$gatewayName];
    }
}
