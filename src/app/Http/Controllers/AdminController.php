<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Services\Delivery\DeliveryManager;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'total_revenue' => Order::where('status', 'paid')->sum('usd_amount'),
            'products' => Product::count(),
        ];

        $orders = Order::with('product')->latest()->take(20)->get();

        return view('admin.dashboard', compact('stats', 'orders'));
    }

    public function deliver(Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Order not paid yet.');
        }

        $product = $order->product;
        $content = "Thank you for your purchase!\n\nProduct: {$product->name}\nOrder: {$order->order_id}\n\nDownload: " . route('order.download', ['orderId' => $order->order_id]);

        app(DeliveryManager::class)->send($order, $content);

        $order->update(['status' => 'delivered', 'delivered_at' => now()]);

        return back()->with('success', 'Product delivered.');
    }

    public function markPaid(Order $order)
    {
        $order->update(['status' => 'paid', 'paid_at' => now()]);

        if ($order->payment) {
            $order->payment->update(['status' => 'paid', 'verified_at' => now()]);
        }

        return back()->with('success', 'Order marked as paid.');
    }

    public function products()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'currency' => 'required|in:btc,lightning,usdc,xmr',
            'price_usd' => 'nullable|numeric',
            'file' => 'nullable|file|max:102400',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('products', 'local');
            $validated['file_path'] = basename($path);
        }

        Product::create($validated);

        return back()->with('success', 'Product created.');
    }
}