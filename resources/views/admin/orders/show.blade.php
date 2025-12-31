@extends('layouts.app')

@section('title', 'Order Details - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Order {{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Orders
            </a>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Order Status --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Order Status</p>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                @if($order->status === 'delivered') bg-green-100 text-green-800
                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Payment Status</p>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ ucfirst($order->payment_status) }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Payment Method</p>
            <p class="font-semibold text-gray-900">{{ ucfirst($order->payment_method) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Amount</p>
            <p class="text-2xl font-bold text-primary-600">${{ number_format($order->total_amount, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Order Items --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 py-3 border-b border-gray-200 last:border-b-0">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-grow">
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</p>
                            @if($item->product)
                                <a href="{{ route('admin.products.edit', $item->product) }}" class="text-sm text-primary-600 hover:text-primary-700">
                                    Edit Product →
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Shipping</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span class="text-primary-600">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer & Shipping Info --}}
        <div class="lg:col-span-1">
            {{-- Customer Info --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-3">Customer Information</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-600">Name</p>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-3">Shipping Address</h3>
                <div class="text-sm text-gray-700">
                    <p class="font-medium">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.orders.edit', $order) }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center font-medium py-2 px-4 rounded-lg transition-colors">
                        Update Status
                    </a>
                    <a href="{{ route('checkout.success', $order) }}" target="_blank" class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 text-center font-medium py-2 px-4 rounded-lg transition-colors">
                        View as Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
