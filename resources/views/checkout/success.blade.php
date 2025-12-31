@extends('layouts.app')

@section('title', 'Order Confirmation - TechShop')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    {{-- Success Icon --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
        <p class="text-gray-600">Thank you for your purchase. Your order has been received.</p>
    </div>

    {{-- Order Details --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Order Number</p>
                <p class="text-lg font-bold text-gray-900">{{ $order->order_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Order Date</p>
                <p class="text-lg font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-2xl font-bold text-primary-600">${{ number_format($order->total_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Payment Status</p>
                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h3 class="font-bold text-gray-900 mb-3">Shipping Address</h3>
            <div class="text-gray-700">
                <p class="font-medium">{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                <p>{{ $order->shipping_country }}</p>
                <p class="mt-2">
                    <span class="text-sm text-gray-600">Email:</span> {{ $order->shipping_email }}
                </p>
                <p>
                    <span class="text-sm text-gray-600">Phone:</span> {{ $order->shipping_phone }}
                </p>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Order Items</h3>
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
                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</p>
                    <p class="text-sm text-gray-600">${{ number_format($item->unit_price, 2) }} each</p>
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

    {{-- Next Steps --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="font-bold text-blue-900 mb-3">What's Next?</h3>
        <ul class="space-y-2 text-blue-800">
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>You'll receive an order confirmation email at {{ $order->shipping_email }}</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>We'll send you shipping updates as your order is processed</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>You can track your order status in your account</span>
            </li>
        </ul>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('orders.index') }}" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-center font-semibold py-3 px-6 rounded-lg transition-colors">
            View My Orders
        </a>
        <a href="{{ route('products.index') }}" class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 text-center font-semibold py-3 px-6 rounded-lg transition-colors">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
