@extends('layouts.app')

@section('title', 'My Orders - TechShop')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">My Orders</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($orders->isEmpty())
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">No orders yet</h2>
            <p class="text-gray-500 mb-6">Start shopping to create your first order!</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                Browse Products
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                {{-- Order Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Order Number</p>
                            <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Date Placed</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Total Amount</p>
                            <p class="font-bold text-primary-600">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm text-primary-600 hover:text-primary-700">
                                        View Product
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Order Summary --}}
                    <div class="border-t border-gray-200 mt-6 pt-4">
                        <div class="flex justify-end">
                            <div class="w-64 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium">${{ number_format($order->shipping_cost, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium">${{ number_format($order->tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                                    <span>Total</span>
                                    <span class="text-primary-600">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Info --}}
                    <div class="border-t border-gray-200 mt-6 pt-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Shipping Address</h4>
                        <div class="text-sm text-gray-700">
                            <p class="font-medium">{{ $order->shipping_name }}</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                            <p>{{ $order->shipping_country }}</p>
                        </div>
                    </div>

                    {{-- Order Actions --}}
                    <div class="mt-6 flex justify-end space-x-3">
                        @if($order->status === 'pending')
                            <button class="px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50 text-sm font-medium">
                                Cancel Order
                            </button>
                        @endif
                        <a href="{{ route('checkout.success', $order) }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
