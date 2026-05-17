@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-lg mx-auto">
    <a href="{{ route('store.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to products
    </a>

    <div class="gradient-border rounded-2xl bg-gray-900/50 backdrop-blur-sm p-6 sm:p-8 mb-6">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-800">
            <div>
                <h1 class="text-2xl font-bold">Checkout</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $product->name }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">${{ number_format($product->price_usd, 0) }}</div>
                <div class="text-xs text-gray-600 font-mono">≈ {{ $product->price }} BTC</div>
            </div>
        </div>

        <form method="POST" action="{{ route('store.place-order') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Pay with</label>
                <select name="currency" class="w-full bg-gray-950 border border-gray-800 rounded-xl p-3 text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition">
                    <option value="btc">Bitcoin (BTC)</option>
                    <option value="lightning">Lightning (sats)</option>
                    <option value="usdc">USDC (Ethereum)</option>
                    <option value="xmr">Monero (XMR)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Delivery method</label>
                <select name="delivery_method" class="w-full bg-gray-950 border border-gray-800 rounded-xl p-3 text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition">
                    <option value="email">Email</option>
                    <option value="telegram">Telegram</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Contact</label>
                <input type="text" name="delivery_contact" required placeholder="you@email.com / @username / +1234567890"
                       class="w-full bg-gray-950 border border-gray-800 rounded-xl p-3 text-white placeholder-gray-600 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition">
                <p class="text-xs text-gray-700 mt-1.5">Where to send your purchase</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">
                    PIN
                    <span class="text-gray-700 font-normal">(optional, 4 digits)</span>
                </label>
                <input type="text" name="pin" maxlength="4" pattern="[0-9]{4}" placeholder="8412"
                       class="w-full bg-gray-950 border border-gray-800 rounded-xl p-3 text-white placeholder-gray-600 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition">
                <p class="text-xs text-gray-700 mt-1.5">Protects your order status page</p>
            </div>

            <button type="submit"
                    class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-200 glow mt-2">
                Create Order — ${{ number_format($product->price_usd, 0) }}
            </button>
        </form>
    </div>
</div>
@endsection
