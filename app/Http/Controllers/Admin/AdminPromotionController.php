<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        if ($request->filled('applies_to')) {
            $query->where('applies_to', $request->applies_to);
        }

        $promotions = $query->latest()->paginate(20);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $categories = Category::all();
        $products = Product::where('is_active', true)->get();

        return view('admin.promotions.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'applies_to' => 'required|in:product,category',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'required_if:applies_to,product|array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'required_if:applies_to,category|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $promotion = Promotion::create($validated);

        if ($validated['applies_to'] === 'product') {
            $promotion->products()->attach($validated['product_ids'] ?? []);
        } else {
            $promotion->categories()->attach($validated['category_ids'] ?? []);
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion)
    {
        return redirect()->route('admin.promotions.edit', $promotion);
    }

    public function edit(Promotion $promotion)
    {
        $categories = Category::all();
        $products = Product::where('is_active', true)->get();
        $promotion->load('products', 'categories');

        return view('admin.promotions.edit', compact('promotion', 'categories', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'applies_to' => 'required|in:product,category',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'required_if:applies_to,product|array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'required_if:applies_to,category|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $promotion->update($validated);

        if ($validated['applies_to'] === 'product') {
            $promotion->products()->sync($validated['product_ids'] ?? []);
            $promotion->categories()->sync([]);
        } else {
            $promotion->categories()->sync($validated['category_ids'] ?? []);
            $promotion->products()->sync([]);
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
}
