@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="text-center mb-12">
    <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">
        Digital <span class="text-brand-400">Products</span>
    </h1>
    <p class="text-gray-500 mt-3 max-w-lg mx-auto text-lg">
        Pay with crypto. No account required. Instant delivery.
    </p>
</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
    @forelse($products as $i => $product)
    <div class="gradient-border rounded-2xl bg-gray-900/50 backdrop-blur-sm p-6 card-hover flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center mb-4">
            <span class="text-brand-400 font-bold text-lg">{{ $i + 1 }}</span>
        </div>

        <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
        <p class="text-gray-500 text-sm leading-relaxed mb-6 flex-1">{{ $product->description }}</p>

        <div class="flex items-baseline gap-2 mb-4">
            <span class="text-3xl font-bold text-white">${{ number_format($product->price_usd, 0) }}</span>
            <span class="text-gray-600 text-sm">USD</span>
        </div>

        <div class="text-xs text-gray-600 font-mono mb-5">
            ≈ {{ $product->price }} BTC
        </div>

        <a href="{{ route('store.checkout', $product->slug) }}"
           class="inline-flex items-center justify-center gap-2 w-full bg-brand-600 hover:bg-brand-500 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 glow">
            Purchase
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
    @empty
    <div class="col-span-full text-center text-gray-600 py-20">
        <span class="text-5xl block mb-4">📦</span>
        <p class="text-lg">No products available yet.</p>
    </div>
    @endforelse
</div>

<div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
    <div class="text-center p-4">
        <div class="text-2xl mb-1">🔒</div>
        <div class="text-sm font-medium text-gray-400">No Account</div>
        <div class="text-xs text-gray-600">Buy anonymously</div>
    </div>
    <div class="text-center p-4">
        <div class="text-2xl mb-1">⚡</div>
        <div class="text-sm font-medium text-gray-400">Instant Delivery</div>
        <div class="text-xs text-gray-600">Via Email / Telegram / WhatsApp</div>
    </div>
    <div class="text-center p-4">
        <div class="text-2xl mb-1">🪙</div>
        <div class="text-sm font-medium text-gray-400">Multiple Currencies</div>
        <div class="text-xs text-gray-600">BTC · Lightning · USDC · XMR</div>
    </div>
</div>
@endsection
