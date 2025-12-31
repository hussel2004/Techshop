@extends('layouts.app')

@section('title', 'Manage Categories - Admin')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Manage Categories</h1>
                <p class="text-gray-600 mt-1">Organize your products into categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                + Add New Category
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($categories->isEmpty())
        <div class="bg-white rounded-lg shadow-md text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">No categories yet</h2>
            <p class="text-gray-500 mb-6">Create your first category to organize products</p>
            <a href="{{ route('admin.categories.create') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                + Add New Category
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($category->description, 80) }}</p>

                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                        <div class="text-sm">
                            <span class="text-gray-600">Products:</span>
                            <span class="font-semibold text-gray-900">{{ $category->products_count }}</span>
                        </div>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" target="_blank" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            View Products →
                        </a>
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-center font-medium py-2 px-4 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure? This will delete all products in this category!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full border border-red-300 hover:bg-red-50 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
            <div class="mt-8">
                {{ $categories->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
