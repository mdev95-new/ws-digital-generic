<?php

namespace App\Services\Delivery;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class TelegramService implements DeliveryInterface
{
    public function method(): string
    {
        return 'telegram';
    }

    public function send(Order $order, string $content): bool
    {
        $token = config('delivery.methods.telegram.bot_token');
        if (!$token) return false;

        $chatId = ltrim($order->delivery_contact, '@');

        $response = Http::post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text' => $content,
                'parse_mode' => 'HTML',
            ]
        );

        return $response->successful();
    }
}