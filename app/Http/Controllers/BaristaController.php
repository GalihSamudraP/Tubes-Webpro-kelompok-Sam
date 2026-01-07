<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class BaristaController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'items.product')->latest()->get();
        return view('barista.dashboard', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $updateData = ['status' => $request->status];

        // Assign barista if they start processing the order
        if ($request->status === 'processing') {
            $updateData['barista_id'] = auth()->id();
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Order status updated!');
    }
}
