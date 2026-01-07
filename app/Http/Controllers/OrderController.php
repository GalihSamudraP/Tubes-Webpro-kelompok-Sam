<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->get();
        return view('client.orders', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $orderItemsData = [];

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $totalPrice += $product->price * $item['quantity'];
            $orderItemsData[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        $shippingFee = 15000;
        $discount = 0;

        if (Auth::user()->is_premium) {
            $shippingFee = 0;
            $discount = 0.10;
        }

        $discountAmount = $totalPrice * $discount;
        $finalTotal = $totalPrice - $discountAmount + $shippingFee;

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $finalTotal,
            'status' => 'pending',
            'payment_status' => 'paid', // Simulating payment
        ]);

        foreach ($orderItemsData as $data) {
            $order->items()->create($data);
        }

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
