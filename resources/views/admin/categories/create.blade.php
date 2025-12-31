@extends('layouts.app')

@section('title', 'Add New Category - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Add New Category</h1>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Categories
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Category Name --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Brief description of what products belong in this category</p>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
