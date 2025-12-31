@extends('layouts.app')

@section('title', 'Home - TechShop E-Commerce')

@section('content')
{{-- Hero Section --}}
<div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl font-bold mb-6">Welcome to TechShop</h1>
                <p class="text-xl mb-8 text-primary-100">Discover the latest in mobile phones, computers, telephones, and accessories. Quality products at unbeatable prices!</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-lg transition-colors">
                    Shop Now
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
            <div class="hidden md:flex justify-center">
                <svg class="w-80 h-80 text-primary-500 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 2H7c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H7V4h10v16z"/>
                    <path d="M12 17c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Features Section --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Free Shipping</h3>
            <p class="text-gray-600">On orders over $50</p>
        </div>

        <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.040A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Secure Payment</h3>
            <p class="text-gray-600">100% secure transactions</p>
        </div>

        <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">30-Day Returns</h3>
            <p class="text-gray-600">Money-back guarantee</p>
        </div>

        <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">24/7 Support</h3>
            <p class="text-gray-600">Always here to help</p>
        </div>
    </div>
</div>

{{-- Categories Section --}}
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Shop by Category</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow p-8 text-center">
                    <div class="mb-4">
                        @if($category->slug === 'mobile-phones')
                            <svg class="w-16 h-16 mx-auto text-primary-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        @elseif($category->slug === 'computers')
                            <svg class="w-16 h-16 mx-auto text-primary-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        @elseif($category->slug === 'telephones')
                            <svg class="w-16 h-16 mx-auto text-primary-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        @else
                            <svg class="w-16 h-16 mx-auto text-primary-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $category->name }}</h3>
                    <p class="text-gray-600">{{ $category->products_count }} products</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Featured Products Section --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
            <div class="relative">
                <a href="{{ route('products.show', $product->slug) }}">
                    @if($product->primaryImage)
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="h-48 w-full object-cover rounded-t-lg">
                    @else
                        <div class="h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </a>
                <span class="absolute top-2 left-2 inline-block bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                    {{ $product->category->name }}
                </span>
                @if($product->has_active_promotion)
                    <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                        {{ $product->active_promotion->discount_percentage }}% OFF
                    </div>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $product->brand }}</p>
                <div class="flex items-center justify-between">
                    @if($product->has_active_promotion)
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-400 line-through text-sm">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                <span class="text-xl font-bold text-green-600">
                                    ${{ number_format($product->discounted_price, 2) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <span class="text-2xl font-bold text-primary-600">${{ number_format($product->price, 2) }}</span>
                    @endif
                    <a href="{{ route('products.show', $product->slug) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        View
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-12">
        <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
            View All Products
        </a>
    </div>
</div>

{{-- Newsletter Section --}}
<div class="bg-primary-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h3 class="text-3xl font-bold mb-3">Subscribe to Our Newsletter</h3>
                <p class="text-primary-100">Get the latest updates on new products and exclusive offers!</p>
            </div>
            <div>
                <form class="flex gap-2">
                    <input type="email" placeholder="Your email address" class="flex-1 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-white text-gray-900">
                    <button type="submit" class="bg-white text-primary-600 hover:bg-gray-100 font-semibold px-6 py-3 rounded-lg transition-colors">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
