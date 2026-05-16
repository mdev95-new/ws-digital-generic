<?php

namespace App\Services\Delivery;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class WhatsAppService implements DeliveryInterface
{
    public function method(): string
    {
        return 'whatsapp';
    }

    public function send(Order $order, string $content): bool
    {
        $apiKey = config('delivery.methods.whatsapp.api_key');
        $apiUrl = config('delivery.methods.whatsapp.api_url');

        if (!$apiKey) return false;

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
        ])->post("{$apiUrl}/messages", [
            'to' => $order->delivery_contact,
            'type' => 'text',
            'text' => ['body' => $content],
        ]);

        return $response->successful();
    }
}