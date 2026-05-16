@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="py-8">
    <h1 class="text-3xl font-bold mb-2">Digital Store</h1>
    <p class="text-gray-400 mb-8">No account needed. Pay with crypto. Instant delivery.</p>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <div class="bg-gray-900 rounded-lg border border-gray-800 p-6 hover:border-emerald-700 transition">
            <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
            <p class="text-gray-400 text-sm mb-4">{{ $product->description }}</p>
            <div class="flex justify-between items-center">
                <span class="text-emerald-400 font-mono">{{ $product->price }} {{ strtoupper($product->currency) }}</span>
                @if($product->price_usd)
                <span class="text-gray-500 text-sm">~${{ number_format($product->price_usd, 2) }}</span>
                @endif
            </div>
            <a href="{{ route('store.checkout', $product->slug) }}"
               class="mt-4 block text-center bg-emerald-600 hover:bg-emerald-500 py-2 rounded transition">
                Buy Now
            </a>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 py-12">No products available yet.</div>
        @endforelse
    </div>

    <div class="mt-12 text-center text-gray-500 text-sm">
        <p>🔒 No account required &middot; 🔗 Pay with BTC / Lightning / USDC / XMR &middot; ✉️ Delivered via Email / Telegram / WhatsApp</p>
    </div>
</div>
@endsection