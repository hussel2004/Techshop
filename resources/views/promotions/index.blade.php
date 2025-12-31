@extends('layouts.app')

@section('title', 'Super Deals - TechShop')

@section('content')
{{-- Hero Section --}}
<div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Super Deals</h1>
        <p class="text-xl text-primary-100">Limited time offers on your favorite products</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($promotions->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
            </svg>
            <h2 class="text-3xl font-bold text-gray-700 mb-4">No Active Promotions</h2>
            <p class="text-gray-500 text-lg mb-8">Check back soon for amazing deals!</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                Browse All Products
            </a>
        </div>
    @else
        {{-- Promotions List --}}
        <div class="space-y-12">
            @foreach($promotions as $promotion)
                @php
                    // Get all affected products
                    if ($promotion->applies_to === 'product') {
                        $affectedProducts = $promotion->products;
                    } else {
                        $affectedProducts = $promotion->categories->flatMap->products->unique('id');
                    }
                @endphp

                @if($affectedProducts->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        {{-- Promotion Header --}}
                        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h2 class="text-3xl font-bold mb-2">{{ $promotion->name }}</h2>
                                    @if($promotion->description)
                                        <p class="text-red-100">{{ $promotion->description }}</p>
                                    @endif
                                </div>
                                <div class="mt-4 md:mt-0">
                                    <div class="bg-white text-red-600 rounded-full px-6 py-3 inline-block">
                                        <span class="text-4xl font-bold">{{ $promotion->discount_percentage }}%</span>
                                        <span class="text-lg font-semibold ml-1">OFF</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-red-100 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Valid until {{ $promotion->end_date->format('F d, Y') }}</span>
                            </div>
                        </div>

                        {{-- Products Grid --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($affectedProducts->take(8) as $product)
                                    <div class="bg-white rounded-lg border border-gray-200 hover:shadow-xl transition-shadow relative group">
                                        {{-- Discount Badge --}}
                                        <div class="absolute top-2 right-2 z-10 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                            {{ $promotion->discount_percentage }}% OFF
                                        </div>

                                        {{-- Product Image --}}
                                        <a href="{{ route('products.show', $product->slug) }}">
                                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg">
                                        </a>

                                        {{-- Product Info --}}
                                        <div class="p-4">
                                            <a href="{{ route('products.show', $product->slug) }}">
                                                <p class="text-xs text-primary-600 font-semibold mb-1">{{ $product->category->name }}</p>
                                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-primary-600">{{ $product->name }}</h3>
                                            </a>

                                            {{-- Price Display --}}
                                            <div class="mb-3">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-gray-400 line-through text-sm">
                                                        ${{ number_format($product->price, 2) }}
                                                    </span>
                                                    <span class="text-green-600 font-bold text-xl">
                                                        ${{ number_format($product->discounted_price, 2) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-green-600 font-semibold mt-1">
                                                    You save ${{ number_format($product->price - $product->discounted_price, 2) }}
                                                </p>
                                            </div>

                                            {{-- View Details Button --}}
                                            <a href="{{ route('products.show', $product->slug) }}" class="block w-full bg-primary-600 text-white text-center py-2 rounded-md hover:bg-primary-700 transition-colors font-semibold">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($affectedProducts->count() > 8)
                                <div class="text-center mt-6">
                                    <p class="text-gray-600">+ {{ $affectedProducts->count() - 8 }} more products on sale</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
