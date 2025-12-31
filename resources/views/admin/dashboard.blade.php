@extends('layouts.app')

@section('title', 'Admin Dashboard - TechShop')

@section('content')
<div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <p class="text-primary-100 mt-1">Manage your e-commerce store</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Products --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-3 inline-block">Manage Products →</a>
        </div>

        {{-- Total Categories --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Categories</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalCategories }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">Manage Categories →</a>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-purple-600 hover:text-purple-700 mt-3 inline-block">View Orders →</a>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-3">From completed orders</p>
        </div>
    </div>

    {{-- Active Promotions Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Promotions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activePromotions }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="text-sm text-red-600 hover:text-red-700 mt-3 inline-block">Manage Promotions →</a>
        </div>
    </div>

    {{-- Recent Orders & Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View All →</a>
                </div>

                @if($recentOrders->isEmpty())
                    <p class="text-gray-500 text-center py-8">No orders yet</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order #</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-600 hover:text-primary-700">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                            @if($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.products.create') }}" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                        + Add New Product
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                        + Add New Category
                    </a>
                    <a href="{{ route('admin.promotions.create') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                        + Create Promotion
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                        View All Orders
                    </a>
                    <a href="{{ route('products.index') }}" class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                        View Store Front
                    </a>
                </div>
            </div>

            {{-- Low Stock Alert --}}
            @if($lowStockProducts->isNotEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Low Stock Alert</h2>
                <div class="space-y-3">
                    @foreach($lowStockProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $product->name }}</p>
                            <p class="text-xs text-red-600">Only {{ $product->stock_quantity }} left</p>
                        </div>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            Update →
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
