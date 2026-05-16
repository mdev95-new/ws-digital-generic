<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\Payment\PaymentManager;
use App\Services\Delivery\DeliveryManager;
use Illuminate\Console\Command;

class VerifyPayments extends Command
{
    protected $signature = 'payments:verify';
    protected $description = 'Verify pending crypto payments';

    public function handle(PaymentManager $payments, DeliveryManager $delivery)
    {
        $orders = Order::where('status', 'pending')
            ->where('expires_at', '>', now())
            ->get();

        foreach ($orders as $order) {
            $status = $payments->verifyPayment($order->order_id, $order->currency);

            if ($status === 'paid') {
                $this->info("Order {$order->order_id} paid!");

                $product = $order->product;
                $content = "Thank you for your purchase!\n\nProduct: {$product->name}\nOrder: {$order->order_id}\n\nDownload: " . route('order.download', ['orderId' => $order->order_id]);

                $delivery->send($order, $content);
                $order->update(['status' => 'delivered', 'delivered_at' => now()]);
            }
        }

        Order::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info('Done.');
    }
}