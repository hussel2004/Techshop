@extends('layouts.app')

@section('title', 'Update Order - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Update Order Status</h1>
                <p class="text-gray-600 mt-1">Order: {{ $order->order_number }}</p>
            </div>
            <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Order
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Order Summary --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Customer</p>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Order Date</p>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="font-medium text-primary-600">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Items</p>
                        <p class="font-medium text-gray-900">{{ $order->items->count() }} product(s)</p>
                    </div>
                </div>
            </div>

            {{-- Order Status --}}
            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Order Status *</label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Update the current status of the order</p>
            </div>

            {{-- Payment Status --}}
            <div class="mb-6">
                <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-2">Payment Status *</label>
                <select name="payment_status" id="payment_status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                    <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('payment_status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Internal notes (not visible to customer)</p>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.orders.show', $order) }}" class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
