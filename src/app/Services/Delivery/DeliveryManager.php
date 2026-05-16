<?php

namespace App\Services\Delivery;

use App\Models\Order;
use InvalidArgumentException;

class DeliveryManager
{
    private array $methods = [];

    public function __construct()
    {
        $this->methods = [
            'email' => new EmailService(),
            'telegram' => new TelegramService(),
            'whatsapp' => new WhatsAppService(),
        ];
    }

    public function driver(?string $method = null): DeliveryInterface
    {
        $method = $method ?? config('delivery.default');

        if (!isset($this->methods[$method])) {
            throw new InvalidArgumentException("Unsupported delivery method: {$method}");
        }

        return $this->methods[$method];
    }

    public function send(Order $order, string $content): bool
    {
        return $this->driver($order->delivery_method)->send($order, $content);
    }

    public function available(): array
    {
        return ['email', 'telegram', 'whatsapp'];
    }
}