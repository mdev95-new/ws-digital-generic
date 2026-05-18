@extends('layouts.app')
@section('title', 'Pay')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="animate-in">
        <div class="w-16 h-16 rounded-2xl bg-brand-500/10 flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="text-2xl font-bold mb-1">Order Created</h1>
        <p class="text-gray-500 text-sm mb-8 max-w-md mx-auto">Send the exact amount shown below. Your order will be processed automatically once the transaction confirms.</p>
    </div>

    <div class="gradient-border rounded-2xl bg-gray-900/40 backdrop-blur-sm p-6 sm:p-8 mb-6 animate-in-d1">
        <div class="text-5xl font-extrabold text-brand-400 font-mono mb-3">
            {{ $payment['amount'] ?? $order->crypto_amount }} {{ strtoupper($payment['currency'] ?? $order->currency) }}
        </div>
        <div class="text-sm text-gray-600 mb-6">
            ≈ ${{ number_format($order->usd_amount, 2) }} USD
        </div>

        @if(isset($payment['invoice']))
            <div class="bg-white p-5 rounded-2xl inline-block mb-5 shadow-lg">
                {!! QrCode::size(220)->generate($payment['invoice']) !!}
            </div>
            <div class="bg-gray-950 rounded-xl p-4 text-left">
                <div class="text-xs text-gray-600 mb-1.5 font-medium">Lightning Invoice</div>
                <p class="text-xs text-gray-400 break-all font-mono leading-relaxed select-all">{{ $payment['invoice'] }}</p>
            </div>
        @elseif(isset($payment['address']))
            <div class="bg-white p-5 rounded-2xl inline-block mb-5 shadow-lg">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ $payment['address'] }}" alt="QR">
            </div>
            <div class="bg-gray-950 rounded-xl p-4 text-left">
                <div class="text-xs text-gray-600 mb-1.5 font-medium">{{ strtoupper($payment['currency'] ?? $order->currency) }} Address</div>
                <p class="text-xs text-gray-400 break-all font-mono leading-relaxed select-all">{{ $payment['address'] }}</p>
            </div>
        @endif
    </div>

    <div class="gradient-border rounded-2xl bg-gray-900/40 backdrop-blur-sm p-5 mb-6 animate-in-d2">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-xs text-gray-600 mb-0.5">Order ID</div>
                <div class="font-mono text-brand-400 text-xs font-semibold">{{ $order->order_id }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-600 mb-0.5">Product</div>
                <div class="text-xs font-medium">{{ $order->product->name }}</div>
            </div>
            @if($order->pin_hash)
            <div>
                <div class="text-xs text-gray-600 mb-0.5">PIN</div>
                <div class="text-xs text-yellow-500 font-mono">●●●● (saved)</div>
            </div>
            @endif
            <div>
                <div class="text-xs text-gray-600 mb-0.5">Delivery</div>
                <div class="text-xs">{{ ucfirst($order->delivery_method) }}</div>
            </div>
        </div>
    </div>

    <div class="animate-in-d2 space-y-3">
        <p class="text-gray-600 text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            Save your Order ID{{ $order->pin_hash ? ' and PIN' : '' }} to check status later
        </p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('order.status', $order->order_id) }}"
               class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white font-medium py-2.5 px-5 rounded-xl transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                Check Status
            </a>
            <a href="{{ route('order.refresh', $order->order_id) }}"
               class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-500 text-white font-medium py-2.5 px-5 rounded-xl transition text-sm glow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh
            </a>
        </div>
    </div>
</div>
@endsection
