<?php

namespace App\Services\Delivery;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class EmailService implements DeliveryInterface
{
    public function method(): string
    {
        return 'email';
    }

    public function send(Order $order, string $content): bool
    {
        try {
            Mail::raw($content, function ($message) use ($order) {
                $message->to($order->delivery_contact)
                    ->subject("Your digital product - Order {$order->order_id}")
                    ->from(config('delivery.methods.email.from'));
            });
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}