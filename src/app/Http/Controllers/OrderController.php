<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Delivery\DeliveryManager;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function status(string $orderId)
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return redirect('/')->with('error', 'Order not found.');
        }

        return view('order.status', compact('order'));
    }

    public function verifyPin(Request $request, string $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();

        $request->validate(['pin' => 'required|digits:4|numeric']);

        if (!$order->verifyPin($request->pin)) {
            return back()->with('error', 'Invalid PIN.');
        }

        return redirect()->route('order.status', ['orderId' => $orderId])
            ->with('verified', true);
    }

    public function confirmation(string $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $payment = session('payment', []);

        return view('store.confirmation', compact('order', 'payment'));
    }

    public function refresh(string $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();

        app(PaymentManager::class)->verifyPayment(
            $order->order_id,
            $order->currency
        );

        $order->refresh();

        if ($order->status === 'paid') {
            app(DeliveryManager::class)->send(
                $order,
                "Your product is ready!\nOrder: {$order->order_id}\nDownload: " . route('order.download', ['orderId' => $order->order_id])
            );
        }

        return response()->json(['status' => $order->status]);
    }

    public function download(string $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();

        if ($order->status !== 'paid') {
            return back()->with('error', 'Order not yet paid.');
        }

        $product = $order->product;

        if (!$product->file_path || !file_exists(storage_path("app/products/{$product->file_path}"))) {
            return back()->with('error', 'File not available.');
        }

        return response()->download(storage_path("app/products/{$product->file_path}"));
    }
}