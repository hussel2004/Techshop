@extends('layouts.app')

@section('title', 'Shopping Cart - TechShop')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Shopping Cart</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($cartItems->isEmpty())
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6">Add some products to get started!</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    @foreach($cartItems as $item)
                    <div class="p-6 border-b border-gray-200 last:border-b-0">
                        <div class="flex items-center space-x-4">
                            {{-- Product Image --}}
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-primary-600">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600">{{ $item->product->brand }}</p>
                                <p class="text-lg font-bold text-primary-600 mt-2">${{ number_format($item->product->price, 2) }}</p>
                            </div>

                            {{-- Quantity & Actions --}}
                            <div class="flex flex-col items-end space-y-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="text-sm text-gray-600">Qty:</label>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="w-16 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <button type="submit" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                        Update
                                    </button>
                                </form>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remove
                                    </button>
                                </form>

                                <p class="text-sm text-gray-600">
                                    Subtotal: <span class="font-semibold">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">
                                @if($subtotal >= 50)
                                    <span class="text-green-600">FREE</span>
                                @else
                                    $10.00
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (8%)</span>
                            <span class="font-semibold">${{ number_format($subtotal * 0.08, 2) }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-primary-600">
                                ${{ number_format($subtotal + ($subtotal >= 50 ? 0 : 10) + ($subtotal * 0.08), 2) }}
                            </span>
                        </div>
                    </div>

                    @if($subtotal < 50)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                            <p class="text-sm text-yellow-800">
                                Add ${{ number_format(50 - $subtotal, 2) }} more to get <span class="font-semibold">FREE shipping</span>!
                            </p>
                        </div>
                    @endif

                    @auth
                        <a href="{{ route('checkout.index') }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center font-semibold py-3 px-6 rounded-lg transition-colors">
                            Proceed to Checkout
                        </a>
                    @else
                        <a href="/login" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center font-semibold py-3 px-6 rounded-lg transition-colors mb-2">
                            Login to Checkout
                        </a>
                        <p class="text-sm text-gray-600 text-center">
                            New customer? <a href="/register" class="text-primary-600 hover:text-primary-700 font-medium">Create an account</a>
                        </p>
                    @endauth

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-start space-x-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Secure Checkout</p>
                                <p>Your information is protected by 256-bit SSL encryption</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
