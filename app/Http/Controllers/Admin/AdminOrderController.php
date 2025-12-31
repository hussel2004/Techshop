<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Search filter
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return redirect()->route('admin.orders.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        return redirect()->route('admin.orders.index')
            ->with('error', 'Orders cannot be deleted. You can cancel them instead.');
    }
}
