<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{
    public function index()
    {
        $products = Product::where('active', true)->get();
        return view('store.index', compact('products'));
    }

    public function checkout(Product $product)
    {
        if (!$product->active) {
            return redirect('/')->with('error', 'Product not available.');
        }
        return view('store.checkout', compact('product'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'currency' => 'required|in:btc,lightning,usdc,xmr',
            'delivery_method' => 'required|in:email,telegram,whatsapp',
            'delivery_contact' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $method = $request->input('delivery_method');
                    match ($method) {
                        'email' => !filter_var($value, FILTER_VALIDATE_EMAIL) ? $fail('Valid email required.') : null,
                        'telegram' => !preg_match('/^@?[a-zA-Z0-9_]{5,32}$/', $value) ? $fail('Valid Telegram @username required.') : null,
                        'whatsapp' => !preg_match('/^\+?[1-9][0-9]{6,14}$/', $value) ? $fail('Valid phone number required.') : null,
                        default => $fail('Invalid delivery method.'),
                    };
                },
            ],
            'pin' => 'nullable|digits:4|numeric',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $orderId = 'DG-'
            . strtoupper(bin2hex(random_bytes(5))) . '-'
            . strtoupper(bin2hex(random_bytes(3)));

        $order = $product->orders()->create([
            'order_id' => $orderId,
            'currency' => $validated['currency'],
            'crypto_amount' => $product->price,
            'usd_amount' => $product->price_usd ?? 0,
            'status' => 'pending',
            'delivery_method' => $validated['delivery_method'],
            'delivery_contact' => $validated['delivery_contact'],
            'pin_hash' => $validated['pin'] ? bcrypt($validated['pin']) : null,
            'expires_at' => now()->addHours(24),
        ]);

        $paymentInfo = app(PaymentManager::class)->createInvoice($order);

        return redirect()->route('order.confirmation', [
            'orderId' => $order->order_id,
        ])->with('payment', $paymentInfo);
    }
}