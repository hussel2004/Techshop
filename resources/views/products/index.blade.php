@extends('layouts.app')

@section('title', 'Products - TechShop')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Products</li>
            </ol>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Filters Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h3 class="text-lg font-bold mb-4">Filters</h3>
                <form action="{{ route('products.index') }}" method="GET">
                    {{-- Search --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>

                    {{-- Category --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>

                    {{-- Sort By --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Latest</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition-colors mb-2">
                        Apply Filters
                    </button>
                    <a href="{{ route('products.index') }}" class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors">
                        Clear Filters
                    </a>
                </form>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="lg:col-span-3">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Products</h2>
                <p class="text-gray-600">{{ $products->total() }} products found</p>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your filters or search query</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium px-6 py-2 rounded-md">
                        View All Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
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
                            @if($product->has_active_promotion)
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                    {{ $product->active_promotion->discount_percentage }}% OFF
                                </div>
                            @elseif($product->stock_quantity < 10)
                                <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                    Only {{ $product->stock_quantity }} left
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <span class="inline-block bg-green-500 text-white text-xs px-2 py-1 rounded-full mb-2">
                                {{ $product->category->name }}
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $product->brand }}</p>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $product->description }}</p>
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
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
