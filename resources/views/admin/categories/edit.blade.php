@extends('layouts.app')

@section('title', 'Edit Category - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Edit Category</h1>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Categories
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Category Name --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Brief description of what products belong in this category</p>
            </div>

            {{-- Category Stats --}}
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-900 mb-2">Category Statistics</h3>
                <p class="text-sm text-blue-800">
                    This category currently has <span class="font-bold">{{ $category->products_count }}</span> product(s)
                </p>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all {{ $category->products_count }} product(s) in this category!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        Delete Category
                    </button>
                </form>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors">
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
