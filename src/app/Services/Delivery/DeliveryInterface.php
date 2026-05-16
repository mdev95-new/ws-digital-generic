<?php

namespace App\Services\Delivery;

use App\Models\Order;

interface DeliveryInterface
{
    public function send(Order $order, string $content): bool;
    public function method(): string;
}