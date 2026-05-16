<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentInterface
{
    public function createInvoice(Order $order): array;
    public function verifyPayment(string $orderId): string;
    public function handleWebhook(array $payload): void;
    public function currency(): string;
}