@extends('layouts.app')
@section('title', 'Order Status')

@section('content')
<div class="py-8 max-w-lg mx-auto text-center">
    <h1 class="text-2xl font-bold mb-2">Order Status</h1>

    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 mb-6">
        <div class="text-3xl font-mono text-emerald-400 mb-4">{{ $order->order_id }}</div>

        <div class="inline-block px-4 py-2 rounded text-sm font-semibold mb-4
            @switch($order->status)
                @case('paid') bg-emerald-900/50 text-emerald-400 @break
                @case('pending') bg-yellow-900/50 text-yellow-400 @break
                @case('delivered') bg-blue-900/50 text-blue-400 @break
                @case('expired') bg-red-900/50 text-red-400 @break
                @default bg-gray-800 text-gray-400
            @endswitch">
            {{ ucfirst($order->status) }}
        </div>

        <div class="text-left space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Product</span>
                <span>{{ $order->product->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Amount</span>
                <span class="font-mono">{{ $order->crypto_amount }} {{ strtoupper($order->currency) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Delivery</span>
                <span>{{ ucfirst($order->delivery_method) }} → {{ $order->delivery_contact }}</span>
            </div>
            @if($order->paid_at)
            <div class="flex justify-between">
                <span class="text-gray-400">Paid at</span>
                <span>{{ $order->paid_at->format('Y-m-d H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($order->status === 'pending')
        <p class="text-gray-500 text-sm mb-4">Waiting for payment. Click refresh to check.</p>
        <a href="{{ route('order.refresh', $order->order_id) }}"
           class="bg-emerald-600 hover:bg-emerald-500 px-6 py-2 rounded transition">
            Refresh Status
        </a>
    @endif

    @if($order->status === 'paid' || $order->status === 'delivered')
        <a href="{{ route('order.download', $order->order_id) }}"
           class="bg-emerald-600 hover:bg-emerald-500 px-6 py-2 rounded transition inline-block mt-4">
            Download Product
        </a>
    @endif
</div>
@endsection