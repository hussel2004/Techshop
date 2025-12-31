@extends('layouts.app')

@section('title', 'Checkout - TechShop')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Checkout</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Checkout Form --}}
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                {{-- Shipping Information --}}
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-6">Shipping Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_name') border-red-500 @enderror">
                            @error('shipping_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_email') border-red-500 @enderror">
                            @error('shipping_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_phone') border-red-500 @enderror">
                            @error('shipping_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address', auth()->user()->address) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_address') border-red-500 @enderror">
                            @error('shipping_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city', auth()->user()->city) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state', auth()->user()->state) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_state') border-red-500 @enderror">
                            @error('shipping_state')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip', auth()->user()->zip) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('shipping_zip') border-red-500 @enderror">
                            @error('shipping_zip')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                            <input type="text" name="shipping_country" value="{{ old('shipping_country', auth()->user()->country ?? 'USA') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-6">Payment Method</h2>

                    <div class="space-y-4">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="credit_card" checked class="mr-3">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="font-medium">Credit / Debit Card</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="paypal" class="mr-3">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.72a.77.77 0 0 1 .759-.639h8.718c2.655 0 4.564 1.422 4.564 3.805 0 3.444-2.437 5.646-6.29 5.646H9.604l-1.586 8.805a.692.692 0 0 1-.682.558zM19.17 7.5c.803 0 1.488.18 2.047.547.828.543 1.277 1.417 1.277 2.476 0 2.82-2.097 4.746-5.246 4.746h-2.845l-1.164 6.462a.693.693 0 0 1-.684.558H7.951l1.357-7.539h3.011c3.854 0 6.29-2.201 6.29-5.646 0-.763-.227-1.418-.647-1.933a3.197 3.197 0 0 0-.792-.671z"/>
                                </svg>
                                <span class="font-medium">PayPal</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash_on_delivery" class="mr-3">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="font-medium">Cash on Delivery</span>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order Notes --}}
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Order Notes (Optional)</h2>
                    <textarea name="notes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Special instructions for delivery...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors text-lg">
                    Place Order
                </button>
            </form>
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                {{-- Cart Items --}}
                <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-3 py-2">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm font-semibold">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-semibold">${{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-semibold">${{ number_format($tax, 2) }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-primary-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-start space-x-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Secure Payment</p>
                            <p>SSL encrypted checkout</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
