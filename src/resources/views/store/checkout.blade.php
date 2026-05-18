@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('store.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-300 transition mb-8 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to products
    </a>

    <div class="grid lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            <div class="gradient-border rounded-2xl bg-gray-900/40 backdrop-blur-sm p-6">
                <div class="flex items-center justify-between mb-5 pb-5 border-b border-gray-800">
                    <div>
                        <h1 class="text-xl font-bold">Checkout</h1>
                        <p class="text-gray-500 text-sm mt-0.5">Complete your purchase</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                        <span class="w-6 h-6 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-semibold">1</span>
                        <span class="w-8 h-0.5 rounded bg-gray-800"></span>
                        <span class="w-6 h-6 rounded-full bg-gray-800 text-gray-500 flex items-center justify-center font-semibold">2</span>
                        <span class="w-8 h-0.5 rounded bg-gray-800"></span>
                        <span class="w-6 h-6 rounded-full bg-gray-800 text-gray-500 flex items-center justify-center font-semibold">3</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('store.place-order') }}" id="checkout-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Pay with</label>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $currencies = [
                                    'btc' => ['label' => 'Bitcoin', 'icon' => '₿', 'note' => 'BTC', 'color' => 'text-orange-400'],
                                    'lightning' => ['label' => 'Lightning', 'icon' => '⚡', 'note' => 'sats', 'color' => 'text-yellow-400'],
                                    'usdc' => ['label' => 'USDC', 'icon' => '◈', 'note' => 'Ethereum', 'color' => 'text-blue-400'],
                                    'xmr' => ['label' => 'Monero', 'icon' => 'ɱ', 'note' => 'XMR', 'color' => 'text-red-400'],
                                ];
                            @endphp
                            @foreach($currencies as $val => $c)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="currency" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="peer sr-only">
                                <div class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-800 bg-gray-950/50 peer-checked:border-brand-500 peer-checked:bg-brand-500/5 peer-checked:shadow-[0_0_0_1px_#10b981] transition-all">
                                    <span class="text-xl {{ $c['color'] }}">{{ $c['icon'] }}</span>
                                    <div>
                                        <div class="text-sm font-medium">{{ $c['label'] }}</div>
                                        <div class="text-xs text-gray-600">{{ $c['note'] }}</div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Delivery method</label>
                        <div class="grid grid-cols-3 gap-3">
                            @php
                                $methods = [
                                    'email' => ['label' => 'Email', 'icon' => '✉️'],
                                    'telegram' => ['label' => 'Telegram', 'icon' => '✈️'],
                                    'whatsapp' => ['label' => 'WhatsApp', 'icon' => '💬'],
                                ];
                            @endphp
                            @foreach($methods as $val => $m)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="delivery_method" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="peer sr-only">
                                <div class="flex flex-col items-center gap-1.5 p-3.5 rounded-xl border border-gray-800 bg-gray-950/50 peer-checked:border-brand-500 peer-checked:bg-brand-500/5 peer-checked:shadow-[0_0_0_1px_#10b981] transition-all text-center">
                                    <span class="text-lg">{{ $m['icon'] }}</span>
                                    <span class="text-xs font-medium">{{ $m['label'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Contact</label>
                            <input type="text" name="delivery_contact" required
                                   placeholder="you@email.com / @username / +1234567890"
                                   class="w-full bg-gray-950 border border-gray-800 rounded-xl p-3.5 text-white placeholder-gray-700 outline-none text-sm">
                            <p class="text-xs text-gray-700 mt-1.5">We'll send your product here</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">
                                PIN
                                <span class="text-gray-600 font-normal">(optional)</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="text" name="pin" maxlength="4" pattern="[0-9]{4}"
                                       placeholder="4 digits"
                                       class="w-32 bg-gray-950 border border-gray-800 rounded-xl p-3.5 text-white placeholder-gray-700 outline-none text-sm text-center tracking-widest font-mono">
                                <div class="flex-1 flex items-center text-xs text-gray-700 pl-2">
                                    Protects your order status page
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-200 glow text-sm flex items-center justify-center gap-2">
                        Pay ${{ number_format($product->price_usd, 0) }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="gradient-border rounded-2xl bg-gray-900/40 backdrop-blur-sm p-6 sticky top-24">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Order summary</h3>

                <div class="flex gap-4 mb-5 pb-5 border-b border-gray-800">
                    <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center shrink-0">
                        <span class="text-brand-400 font-bold">₿</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-sm truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">Digital product</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ $product->name }}</span>
                        <span class="font-medium">${{ number_format($product->price_usd, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>Network fee</span>
                        <span>Included</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-800 text-sm">
                        <span class="font-semibold text-gray-300">Total</span>
                        <span class="font-bold text-brand-400">${{ number_format($product->price_usd, 0) }}</span>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-800 text-xs text-gray-700 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        No account needed
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Instant delivery
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Encrypted transaction
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-800 text-xs text-gray-700">
                    <p class="font-mono">≈ {{ $product->price }} BTC</p>
                    <p class="mt-1">Exchange rate locked for 1 hour</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
