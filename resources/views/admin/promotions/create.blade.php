@extends('layouts.app')

@section('title', 'Create Promotion - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Create New Promotion</h1>
                <p class="text-gray-600 mt-1">Set up a special offer or discount</p>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-lg transition-colors">
                ← Back to Promotions
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form action="{{ route('admin.promotions.store') }}" method="POST" class="bg-white rounded-lg shadow-md">
        @csrf

        <div class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Promotion Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage (%) *</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage') }}" min="0" max="100" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('discount_percentage') border-red-500 @enderror">
                            @error('discount_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Date Range --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Date Range</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Apply To --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Apply Promotion To</h2>

                <div class="space-y-4">
                    <div>
                        <label class="inline-flex items-center mr-6">
                            <input type="radio" name="applies_to" value="product" {{ old('applies_to', 'product') == 'product' ? 'checked' : '' }} class="form-radio text-primary-600" onchange="toggleApplyTo()">
                            <span class="ml-2 text-sm font-medium text-gray-700">Individual Products</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="applies_to" value="category" {{ old('applies_to') == 'category' ? 'checked' : '' }} class="form-radio text-primary-600" onchange="toggleApplyTo()">
                            <span class="ml-2 text-sm font-medium text-gray-700">Product Categories</span>
                        </label>
                    </div>

                    {{-- Product Selector --}}
                    <div id="product-selector" class="{{ old('applies_to', 'product') == 'product' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Products *</label>
                        <div class="border border-gray-300 rounded-lg max-h-64 overflow-y-auto p-4 space-y-2">
                            @foreach($products as $product)
                                <label class="flex items-center hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                                    <span class="ml-2 text-sm">{{ $product->name }} <span class="text-gray-500">({{ $product->category->name }})</span></span>
                                </label>
                            @endforeach
                        </div>
                        @error('product_ids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category Selector --}}
                    <div id="category-selector" class="{{ old('applies_to') == 'category' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Categories *</label>
                        <div class="border border-gray-300 rounded-lg p-4 space-y-2">
                            @foreach($categories as $category)
                                <label class="flex items-center hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                                    <span class="ml-2 text-sm">{{ $category->name }} <span class="text-gray-500">({{ $category->products->count() }} products)</span></span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_ids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-lg">
            <a href="{{ route('admin.promotions.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                Create Promotion
            </button>
        </div>
    </form>
</div>

<script>
function toggleApplyTo() {
    const appliesTo = document.querySelector('input[name="applies_to"]:checked').value;
    const productSelector = document.getElementById('product-selector');
    const categorySelector = document.getElementById('category-selector');

    if (appliesTo === 'product') {
        productSelector.classList.remove('hidden');
        categorySelector.classList.add('hidden');
    } else {
        productSelector.classList.add('hidden');
        categorySelector.classList.remove('hidden');
    }
}
</script>
@endsection
