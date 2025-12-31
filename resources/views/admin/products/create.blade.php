@extends('layouts.app')

@section('title', 'Add New Product - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Add New Product</h1>
            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Products
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Product Name --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="description" rows="5" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Price and Stock --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price ($) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_quantity" class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror">
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Brand --}}
            <div class="mb-6">
                <label for="brand" class="block text-sm font-semibold text-gray-700 mb-2">Brand</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('brand') border-red-500 @enderror">
                @error('brand')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkboxes --}}
            <div class="mb-6 space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">
                        Featured Product (Show on homepage)
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                        Active (Visible on store)
                    </label>
                </div>
            </div>

            {{-- Product Images --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Product Images</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                        class="hidden" onchange="previewImages(event)">
                    <label for="images" class="cursor-pointer">
                        <div class="text-gray-600">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="mt-2 text-sm font-medium">Click to upload product images</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 5MB each</p>
                            <p class="text-xs text-gray-500">You can select multiple images at once</p>
                        </div>
                    </label>
                </div>

                {{-- Image Preview Container --}}
                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>

                @error('images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImages(event) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';

    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                <div class="absolute top-2 right-2">
                    <label class="flex items-center space-x-1 bg-white rounded px-2 py-1 text-xs shadow-md cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="primary_image" value="${i}" ${i === 0 ? 'checked' : ''} class="w-3 h-3">
                        <span>Primary</span>
                    </label>
                </div>
                <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                    Image ${i + 1}
                </div>
            `;
            previewContainer.appendChild(div);
        }

        reader.readAsDataURL(file);
    }
}
</script>
@endpush

@endsection
