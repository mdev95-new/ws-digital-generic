@extends('layouts.app')
@section('title', 'Admin')

@section('content')
<div class="py-8">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-900 border border-gray-800 rounded p-4 text-center">
            <div class="text-2xl font-bold text-emerald-400">{{ $stats['total_orders'] }}</div>
            <div class="text-gray-500 text-sm">Total Orders</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded p-4 text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</div>
            <div class="text-gray-500 text-sm">Pending</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded p-4 text-center">
            <div class="text-2xl font-bold text-blue-400">{{ $stats['paid'] }}</div>
            <div class="text-gray-500 text-sm">Paid</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded p-4 text-center">
            <div class="text-2xl font-bold text-emerald-400">${{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="text-gray-500 text-sm">Revenue</div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left p-2">Order</th>
                        <th class="text-left p-2">Product</th>
                        <th class="text-left p-2">Amount</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">Delivery</th>
                        <th class="text-left p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-b border-gray-800 hover:bg-gray-900">
                        <td class="p-2 font-mono text-xs">{{ $order->order_id }}</td>
                        <td class="p-2">{{ $order->product->name ?? '-' }}</td>
                        <td class="p-2 font-mono">{{ $order->crypto_amount }} {{ strtoupper($order->currency) }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded text-xs
                                @if($order->status === 'paid') bg-emerald-900/50 text-emerald-400
                                @elseif($order->status === 'pending') bg-yellow-900/50 text-yellow-400
                                @else bg-gray-800 text-gray-400 @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-2 text-xs">{{ $order->delivery_method }}</td>
                        <td class="p-2">
                            @if($order->status === 'paid')
                            <form method="POST" action="{{ route('admin.order.deliver', $order) }}" class="inline">
                                @csrf
                                <button class="text-emerald-400 hover:text-emerald-300 text-xs">Deliver</button>
                            </form>
                            @endif
                            @if($order->status === 'pending')
                            <form method="POST" action="{{ route('admin.order.mark-paid', $order) }}" class="inline">
                                @csrf
                                <button class="text-yellow-400 hover:text-yellow-300 text-xs">Mark Paid</button>
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