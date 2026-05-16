<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PinMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $orderId = $request->route('orderId');
        $pin = $request->input('pin');

        $order = \App\Models\Order::where('order_id', $orderId)->first();

        if (!$order) {
            return redirect('/')->with('error', 'Order not found.');
        }

        if ($order->pin_hash && !$order->verifyPin($pin)) {
            return back()->with('error', 'Invalid PIN.');
        }

        $request->attributes->set('order', $order);

        return $next($request);
    }
}