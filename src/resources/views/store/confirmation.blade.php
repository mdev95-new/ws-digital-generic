@extends('layouts.app')
@section('title', 'Pay')

@section('content')
<div class="py-8 max-w-lg mx-auto text-center">
    <h1 class="text-2xl font-bold mb-2">Order Created</h1>
    <p class="text-gray-400 mb-6">Send the exact amount to the address below</p>

    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 mb-6">
        <div class="text-4xl font-mono text-emerald-400 mb-2">
            {{ $payment['amount'] ?? $order->crypto_amount }} {{ strtoupper($payment['currency'] ?? $order->currency) }}
        </div>

        @if(isset($payment['invoice']))
            <div class="bg-white p-4 rounded inline-block mb-4">
                {!! QrCode::size(200)->generate($payment['invoice']) !!}
            </div>
            <p class="text-xs text-gray-500 break-all font-mono">{{ $payment['invoice'] }}</p>
        @elseif(isset($payment['address']))
            <div class="bg-white p-4 rounded inline-block mb-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $payment['address'] }}" alt="QR">
            </div>
            <p class="text-xs text-gray-500 break-all font-mono">{{ $payment['address'] }}</p>
        @endif

        <div class="mt-4 text-sm text-gray-500">
            Order: <span class="font-mono text-emerald-400">{{ $order->order_id }}</span>
            @if($order->pin_hash)
            <br>PIN: <span class="font-mono text-yellow-400">(you chose one)</span>
            @endif
        </div>
    </div>

    <p class="text-gray-500 text-sm mb-4">Write down your Order ID{{ $order->pin_hash ? ' and PIN' : '' }}. You'll need it to check status.</p>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('order.status', $order->order_id) }}"
           class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded transition">
            Check Status
        </a>
        <a href="{{ route('order.refresh', $order->order_id) }}"
           class="bg-emerald-600 hover:bg-emerald-500 px-6 py-2 rounded transition"
           hx-get="{{ route('order.refresh', $order->order_id) }}" hx-target="#status">
            Refresh
        </a>
    </div>
    <div id="status" class="mt-4"></div>
</div>
@endsection