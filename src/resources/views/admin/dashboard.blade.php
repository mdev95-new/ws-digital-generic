@extends('layouts.app')
@section('title', 'Admin')

@section('content')
<div class="py-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <span class="text-xs text-gray-600 font-mono">🔑 admin access</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="gradient-border rounded-xl bg-gray-900/50 p-5 text-center">
            <div class="text-3xl font-bold text-brand-400">{{ $stats['total_orders'] }}</div>
            <div class="text-gray-500 text-sm mt-1">Total Orders</div>
        </div>
        <div class="gradient-border rounded-xl bg-gray-900/50 p-5 text-center">
            <div class="text-3xl font-bold text-yellow-400">{{ $stats['pending'] }}</div>
            <div class="text-gray-500 text-sm mt-1">Pending</div>
        </div>
        <div class="gradient-border rounded-xl bg-gray-900/50 p-5 text-center">
            <div class="text-3xl font-bold text-blue-400">{{ $stats['paid'] }}</div>
            <div class="text-gray-500 text-sm mt-1">Paid</div>
        </div>
        <div class="gradient-border rounded-xl bg-gray-900/50 p-5 text-center">
            <div class="text-3xl font-bold text-brand-400">${{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="text-gray-500 text-sm mt-1">Revenue</div>
        </div>
    </div>

    <div class="gradient-border rounded-xl bg-gray-900/50 p-6">
        <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-600 border-b border-gray-800">
                        <th class="text-left p-2 font-medium">Order</th>
                        <th class="text-left p-2 font-medium">Product</th>
                        <th class="text-left p-2 font-medium">Amount</th>
                        <th class="text-left p-2 font-medium">Status</th>
                        <th class="text-left p-2 font-medium">Delivery</th>
                        <th class="text-left p-2 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-b border-gray-800/60 hover:bg-gray-900/50 transition">
                        <td class="p-2 font-mono text-xs">{{ $order->order_id }}</td>
                        <td class="p-2">{{ $order->product->name ?? '-' }}</td>
                        <td class="p-2 font-mono">{{ $order->crypto_amount }} {{ strtoupper($order->currency) }}</td>
                        <td class="p-2">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                @if($order->status === 'paid') bg-brand-900/50 text-brand-400
                                @elseif($order->status === 'pending') bg-yellow-900/50 text-yellow-400
                                @else bg-gray-800 text-gray-400 @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-2 text-xs text-gray-400">{{ $order->delivery_method }}</td>
                        <td class="p-2">
                            @if($order->status === 'paid')
                            <form method="POST" action="{{ route('admin.order.deliver', $order) }}" class="inline">
                                @csrf
                                <button class="text-brand-400 hover:text-brand-300 text-xs font-medium">Deliver</button>
                            </form>
                            @endif
                            @if($order->status === 'pending')
                            <form method="POST" action="{{ route('admin.order.mark-paid', $order) }}" class="inline">
                                @csrf
                                <button class="text-yellow-400 hover:text-yellow-300 text-xs font-medium">Mark Paid</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection