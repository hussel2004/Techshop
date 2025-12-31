<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Promotion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');
        $activePromotions = Promotion::active()->count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'activePromotions',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
