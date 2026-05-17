@extends('layouts.app')
@section('title', 'Order Status')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="gradient-border rounded-2xl bg-gray-900/50 backdrop-blur-sm p-6 sm:p-8 text-center">
        <div class="flex items-center justify-center gap-4 mb-6">
            <h1 class="text-2xl font-bold">Order Status</h1>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @switch($order->status)
                    @case('paid') bg-brand-900/50 text-brand-400 @break
                    @case('pending') bg-yellow-900/50 text-yellow-400 @break
                    @case('delivered') bg-blue-900/50 text-blue-400 @break
                    @case('expired') bg-red-900/50 text-red-400 @break
                    @default bg-gray-800 text-gray-400
                @endswitch">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="text-3xl font-mono text-brand-400 mb-8 tracking-wider">{{ $order->order_id }}</div>

        <div class="text-left space-y-3 text-sm bg-gray-950 rounded-xl p-5">
            <div class="flex justify-between">
                <span class="text-gray-500">Product</span>
                <span class="font-medium">{{ $order->product->name }}</span>
            </div>
            <div class="flex justify-between border-t border-gray-800 pt-3">
                <span class="text-gray-500">Amount</span>
                <span class="font-mono">{{ $order->crypto_amount }} {{ strtoupper($order->currency) }}</span>
            </div>
            <div class="flex justify-between border-t border-gray-800 pt-3">
                <span class="text-gray-500">Delivery</span>
                <span>{{ ucfirst($order->delivery_method) }} → {{ $order->delivery_contact }}</span>
            </div>
            @if($order->paid_at)
            <div class="flex justify-between border-t border-gray-800 pt-3">
                <span class="text-gray-500">Paid at</span>
                <span>{{ $order->paid_at->format('Y-m-d H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-6 space-y-3">
        @if($order->status === 'pending')
            <p class="text-gray-600 text-sm">Waiting for payment. Click refresh to check.</p>
            <a href="{{ route('order.refresh', $order->order_id) }}"
               class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-500 text-white font-medium py-2.5 px-5 rounded-xl transition text-sm glow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh Status
            </a>
        @endif

        @if($order->status === 'paid' || $order->status === 'delivered')
            <a href="{{ route('order.download', $order->order_id) }}"
               class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-500 text-white font-medium py-2.5 px-5 rounded-xl transition text-sm glow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Product
            </a>
        @endif
    </div>
</div>
@endsection
