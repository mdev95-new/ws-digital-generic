@extends('layouts.app')
@section('title', 'Pay')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="gradient-border rounded-2xl bg-gray-900/50 backdrop-blur-sm p-6 sm:p-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-brand-500/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="text-2xl font-bold mb-1">Order Created</h1>
        <p class="text-gray-500 text-sm mb-6">Send the exact amount to the address below</p>

        <div class="text-4xl font-bold text-brand-400 font-mono mb-6">
            {{ $payment['amount'] ?? $order->crypto_amount }} {{ strtoupper($payment['currency'] ?? $order->currency) }}
        </div>

        @if(isset($payment['invoice']))
            <div class="bg-white p-4 rounded-xl inline-block mb-4">
                {!! QrCode::size(200)->generate($payment['invoice']) !!}
            </div>
            <p class="text-xs text-gray-600 break-all font-mono bg-gray-950 rounded-lg p-3">{{ $payment['invoice'] }}</p>
        @elseif(isset($payment['address']))
            <div class="bg-white p-4 rounded-xl inline-block mb-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $payment['address'] }}" alt="QR">
            </div>
            <p class="text-xs text-gray-600 break-all font-mono bg-gray-950 rounded-lg p-3">{{ $payment['address'] }}</p>
        @endif

        <div class="mt-6 text-sm text-gray-500 border-t border-gray-800 pt-6">
            Order: <span class="font-mono text-brand-400">{{ $order->order_id }}</span>
            @if($order->pin_hash)
                <br>PIN: <span class="font-mono text-yellow-500">(you chose one — keep it safe)</span>
            @endif
        </div>
    </div>

    <div class="text-center mt-6 space-y-3">
        <p class="text-gray-600 text-sm">Save your Order ID{{ $order->pin_hash ? ' and PIN' : '' }} to check status later.</p>
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
