<?php

namespace App\Services\Payment;

use App\Models\Order;
use InvalidArgumentException;

class PaymentManager
{
    private array $methods = [];

    public function __construct()
    {
        $this->methods = [
            'btc' => new BitcoinService(),
            'lightning' => new LightningService(),
            'usdc' => new EvmService(),
            'xmr' => new MoneroService(),
        ];
    }

    public function driver(?string $currency = null): PaymentInterface
    {
        $currency = $currency ?? 'btc';

        if (!isset($this->methods[$currency])) {
            throw new InvalidArgumentException("Unsupported payment method: {$currency}");
        }

        return $this->methods[$currency];
    }

    public function createInvoice(Order $order): array
    {
        return $this->driver($order->currency)->createInvoice($order);
    }

    public function verifyPayment(string $orderId, string $currency): string
    {
        return $this->driver($currency)->verifyPayment($orderId);
    }

    public function handleWebhook(string $currency, array $payload): void
    {
        $this->driver($currency)->handleWebhook($payload);
    }

    public function available(): array
    {
        return ['btc', 'lightning', 'usdc', 'xmr'];
    }
}