<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);

        if (auth()->check()) {
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            $sessionId = session()->getId();
            $cartItem = CartItem::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->quantity = $request->input('quantity', 1);
        $cartItem->save();

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function count()
    {
        $count = $this->getCartItems()->sum('quantity');
        return response()->json(['count' => $count]);
    }

    private function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())
                ->with('product')
                ->get();
        } else {
            return CartItem::where('session_id', session()->getId())
                ->with('product')
                ->get();
        }
    }
}
