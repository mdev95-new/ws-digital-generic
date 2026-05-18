@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="hero-glow relative text-center mb-20 pt-8 sm:pt-12">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-300 text-xs font-medium mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
            Crypto payments · No account · Instant delivery
        </div>
        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.1]">
            Digital goods,<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-emerald-300 to-brand-400">paid in crypto</span>
        </h1>
        <p class="text-gray-500 mt-5 max-w-xl mx-auto text-lg leading-relaxed">
            Buy premium digital products anonymously. Pay with Bitcoin, Lightning,
            USDC or Monero. Get delivered instantly via email or messenger.
        </p>
        <div class="flex items-center justify-center gap-8 mt-8 text-sm text-gray-600">
            <span class="flex items-center gap-2"><span class="text-brand-400">✓</span> No KYC</span>
            <span class="flex items-center gap-2"><span class="text-brand-400">✓</span> Instant delivery</span>
            <span class="flex items-center gap-2"><span class="text-brand-400">✓</span> 24h support</span>
        </div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-5 max-w-5xl mx-auto mb-20">
    @forelse($products as $i => $product)
        @php
            $tiers = ['Starter', 'Pro', 'Enterprise'];
            $features = [
                ['Single license', 'Basic support', 'Email delivery'],
                ['Single license', 'Priority support', 'Multi-delivery', 'Extended license'],
                ['Unlimited license', '24/7 priority support', 'All delivery methods', 'Source code', 'White-label'],
            ];
            $popular = $i === 1;
        @endphp
        <div class="gradient-border rounded-2xl bg-gray-900/40 backdrop-blur-sm p-7 card-hover flex flex-col @if($popular) tier-popular @endif relative">
            @if($popular)
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-brand-600 text-white text-xs font-semibold px-4 py-1 rounded-full">
                    Most popular
                </div>
            @endif

            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold uppercase tracking-widest text-gray-500">{{ $tiers[$i] }}</span>
                <span class="w-9 h-9 rounded-xl bg-brand-500/10 flex items-center justify-center">
                    <span class="text-brand-400 font-bold text-sm">{{ $i + 1 }}</span>
                </span>
            </div>

            <h2 class="text-xl font-bold mb-1">{{ $product->name }}</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-5 flex-1">{{ $product->description }}</p>

            <div class="flex items-baseline gap-1.5 mb-1">
                <span class="text-4xl font-extrabold tracking-tight">${{ number_format($product->price_usd, 0) }}</span>
                <span class="text-gray-600 text-sm">USD</span>
            </div>
            <div class="text-xs text-gray-700 font-mono mb-6">≈ {{ $product->price }} BTC</div>

            <ul class="space-y-2.5 mb-7 text-sm">
                @foreach($features[$i] as $feature)
                    <li class="flex items-center gap-2.5 text-gray-400">
                        <svg class="w-4 h-4 shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>

            <a href="{{ route('store.checkout', $product->slug) }}"
               class="inline-flex items-center justify-center gap-2 w-full @if($popular) bg-brand-600 hover:bg-brand-500 glow @else bg-gray-800 hover:bg-gray-700 @endif text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 text-sm">
                Buy {{ $tiers[$i] }}
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

<div class="max-w-3xl mx-auto text-center mb-16">
    <h3 class="text-lg font-semibold text-gray-300 mb-8">How it works</h3>
    <div class="grid sm:grid-cols-3 gap-8 text-center">
        <div class="animate-in-d1">
            <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <h4 class="font-semibold text-sm mb-1">1. Choose product</h4>
            <p class="text-gray-600 text-xs leading-relaxed">Pick a digital product that fits your needs.</p>
        </div>
        <div class="animate-in-d1">
            <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="font-semibold text-sm mb-1">2. Pay with crypto</h4>
            <p class="text-gray-600 text-xs leading-relaxed">Send BTC, Lightning, USDC or XMR to the given address.</p>
        </div>
        <div class="animate-in-d2">
            <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h4 class="font-semibold text-sm mb-1">3. Get delivered</h4>
            <p class="text-gray-600 text-xs leading-relaxed">Instantly receive your product via email, Telegram or WhatsApp.</p>
        </div>
    </div>
</div>
@endsection
