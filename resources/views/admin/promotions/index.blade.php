@extends('layouts.app')

@section('title', 'Manage Promotions - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Manage Promotions</h1>
                <p class="text-gray-600 mt-1">Create and manage special offers and discounts</p>
            </div>
            <a href="{{ route('admin.promotions.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                + Create New Promotion
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search and Filter --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('admin.promotions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search promotions..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <select name="applies_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="product" {{ request('applies_to') == 'product' ? 'selected' : '' }}>Product-based</option>
                    <option value="category" {{ request('applies_to') == 'category' ? 'selected' : '' }}>Category-based</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.promotions.index') }}" class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 text-center font-semibold px-4 py-2 rounded-lg transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Promotions Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($promotions->isEmpty())
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                <h2 class="text-2xl font-semibold text-gray-700 mb-2">No promotions found</h2>
                <p class="text-gray-500 mb-6">Start by creating your first promotion!</p>
                <a href="{{ route('admin.promotions.create') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                    + Create New Promotion
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Discount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date Range</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($promotions as $promotion)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $promotion->name }}</div>
                                    @if($promotion->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($promotion->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $promotion->applies_to == 'product' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($promotion->applies_to) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-bold text-green-600">{{ $promotion->discount_percentage }}%</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">{{ $promotion->start_date->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $promotion->end_date->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $isActive = $promotion->is_active &&
                                                    now()->between($promotion->start_date, $promotion->end_date);
                                        $isExpired = now()->greaterThan($promotion->end_date);
                                    @endphp
                                    @if($isActive)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                    @elseif($isExpired)
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Expired</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this promotion?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($promotions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $promotions->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
