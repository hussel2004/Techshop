@extends('layouts.app')

@section('title', $product->name . ' - TechShop')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Products</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="text-gray-900 font-medium">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Product Images --}}
        <div>
            {{-- Main Image --}}
            <div class="bg-gray-200 rounded-lg h-96 mb-4 overflow-hidden">
                @if($product->images->count() > 0)
                    <img id="mainImage" src="{{ $product->primaryImage ? $product->primary_image_url : $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Thumbnail Images --}}
            @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <div class="bg-gray-200 rounded-lg h-20 overflow-hidden cursor-pointer hover:ring-2 ring-primary-500 {{ $image->is_primary ? 'ring-2 ring-primary-600' : '' }}"
                             onclick="changeMainImage('{{ $image->image_url }}')">
                            <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            <span class="inline-block bg-green-500 text-white text-sm px-3 py-1 rounded-full mb-3">
                {{ $product->category->name }}
            </span>

            @if($product->has_active_promotion)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <div class="bg-red-500 text-white px-4 py-2 rounded-full text-lg font-bold mr-4">
                            {{ $product->active_promotion->discount_percentage }}% OFF
                        </div>
                        <div>
                            <p class="font-bold text-red-700">{{ $product->active_promotion->name }}</p>
                            @if($product->active_promotion->description)
                                <p class="text-sm text-red-600">{{ $product->active_promotion->description }}</p>
                            @endif
                            <p class="text-xs text-red-500 mt-1">Valid until {{ $product->active_promotion->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            <p class="text-xl text-gray-600 mb-4">{{ $product->brand }}</p>

            <div class="mb-6">
                @if($product->has_active_promotion)
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                        <span class="text-4xl font-bold text-green-600">${{ number_format($product->discounted_price, 2) }}</span>
                    </div>
                    <p class="text-green-600 font-semibold mt-2">You save ${{ number_format($product->price - $product->discounted_price, 2) }}</p>
                @else
                    <span class="text-4xl font-bold text-primary-600">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            {{-- Stock Status --}}
            <div class="mb-6">
                @if($product->stock_quantity > 0)
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">In Stock ({{ $product->stock_quantity }} available)</span>
                    </div>
                @else
                    <div class="flex items-center text-red-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Out of Stock</span>
                    </div>
                @endif
            </div>

            {{-- Add to Cart Form --}}
            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex items-center space-x-4 mb-4">
                        <label class="text-gray-700 font-medium">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Add to Cart
                    </button>
                </form>
            @endif

            {{-- Product Description --}}
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-xl font-bold mb-3">Description</h2>
                <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
            </div>

            {{-- Product Features --}}
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h2 class="text-xl font-bold mb-3">Features</h2>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700">Authentic {{ $product->brand }} product</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700">1-year manufacturer warranty</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700">Free shipping on orders over $50</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700">30-day money-back guarantee</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-8">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <div class="relative">
                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                        @if($relatedProduct->primaryImage)
                            <img src="{{ $relatedProduct->primary_image_url }}" alt="{{ $relatedProduct->name }}" class="h-48 w-full object-cover rounded-t-lg">
                        @else
                            <div class="h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </a>
                    @if($relatedProduct->has_active_promotion)
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            {{ $relatedProduct->active_promotion->discount_percentage }}% OFF
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">{{ $relatedProduct->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $relatedProduct->brand }}</p>
                    <div class="flex items-center justify-between">
                        @if($relatedProduct->has_active_promotion)
                            <div>
                                <div class="flex items-center space-x-1">
                                    <span class="text-gray-400 line-through text-xs">
                                        ${{ number_format($relatedProduct->price, 2) }}
                                    </span>
                                    <span class="text-lg font-bold text-green-600">
                                        ${{ number_format($relatedProduct->discounted_price, 2) }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <span class="text-xl font-bold text-primary-600">${{ number_format($relatedProduct->price, 2) }}</span>
                        @endif
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-md text-sm transition-colors">
                            View
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function changeMainImage(imageUrl) {
    document.getElementById('mainImage').src = imageUrl;
}
</script>
@endpush

@endsection
