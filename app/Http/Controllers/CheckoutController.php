<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $shipping = 10.00;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zip' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $shipping = 10.00;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'shipping_country' => $request->input('shipping_country', 'USA'),
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                    'total_price' => $cartItem->product->price * $cartItem->quantity,
                ]);

                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    private function getCartItems()
    {
        return CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();
    }
}
