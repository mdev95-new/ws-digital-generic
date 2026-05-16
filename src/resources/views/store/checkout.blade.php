@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="py-8 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-2">Checkout</h1>
    <p class="text-gray-400 mb-6">{{ $product->name }} — {{ $product->price }} {{ strtoupper($product->currency) }}</p>

    <form method="POST" action="{{ route('store.place-order') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div>
            <label class="block text-sm text-gray-400 mb-1">Pay with</label>
            <select name="currency" class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white">
                <option value="btc">Bitcoin (BTC)</option>
                <option value="lightning">Lightning (sats)</option>
                <option value="usdc">USDC (Ethereum)</option>
                <option value="xmr">Monero (XMR)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Delivery method</label>
            <select name="delivery_method" class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white">
                <option value="email">Email</option>
                <option value="telegram">Telegram</option>
                <option value="whatsapp">WhatsApp</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Contact (email / @telegram / phone)</label>
            <input type="text" name="delivery_contact" required
                   class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white"
                   placeholder="you@email.com / @username / +1234567890">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">
                PIN (optional — protects your order)
                <span class="text-gray-600">4 digits</span>
            </label>
            <input type="text" name="pin" maxlength="4" pattern="[0-9]{4}"
                   class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white"
                   placeholder="8412">
        </div>

        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 py-3 rounded font-semibold transition">
            Create Order
        </button>
    </form>
</div>
@endsection