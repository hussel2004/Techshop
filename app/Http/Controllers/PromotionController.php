<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::active()
            ->with(['products' => function($query) {
                $query->where('is_active', true)->with('primaryImage', 'category');
            }, 'categories.products' => function($query) {
                $query->where('is_active', true)->with('primaryImage', 'category');
            }])
            ->get();

        return view('promotions.index', compact('promotions'));
    }
}
