<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'nullable|integer|min:1',
        ]);

        $selectedItems = [];
        $totalPrice = 0;

        foreach ($request->products as $item) {
            // Only process if quantity is present (checkbox was checked)
            if (isset($item['quantity']) && $item['quantity'] > 0) {
                $product = Product::find($item['id']);
                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                $selectedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        if (empty($selectedItems)) {
            return redirect()->back()->withErrors(['message' => 'Silakan pilih minimal satu produk.']);
        }

        return view('client.orders.checkout', compact('selectedItems', 'totalPrice'));
    }

    public function showInvoice(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('client.orders.invoice', compact('order'));
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->get();

        if ($request->has('partial')) {
            return view('client.orders.partials.list', compact('orders'));
        }

        return view('client.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        // Check Role - Only Clients can order
        if (Auth::user()->role !== 'client') {
            abort(403, 'Akses ditolak. Admin dan Barista tidak dapat melakukan pemesanan.');
        }

        $request->validate([
            'payment_method' => 'required|in:qris,bank_transfer,virtual_account',
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



        // Shipping fee is 15.000, but units are in thousands (15)
        $shippingFee = 15;
        $discountPercentage = 0;

        // Premium user discount
        if (Auth::user()->is_premium ?? false) {
            $shippingFee = 0;
            // $discountPercentage += 0.10;
        }

        if ($request->payment_method === 'qris') {
            $discountPercentage += 0.10;
        }

        // Apply Promo Code
        if ($request->filled('applied_promo_code')) {
            $promo = \App\Models\Promo::where('code', $request->applied_promo_code)
                ->where('is_active', true)
                ->first();

            if ($promo) {
                // Determine how to stack? For now, add to percentage or sequential?
                // If it is a percentage discount..
                $discountPercentage += ($promo->discount_percentage / 100);
            }
        }

        $discountAmount = $totalPrice * $discountPercentage;
        $finalTotal = $totalPrice - $discountAmount + $shippingFee;

        // Status logic
        $status = 'pending';
        $paymentStatus = 'unpaid';

        // For simplicity as per request "integrasikan agar pembeli bisa membeli dengan baik",
        // we might auto-complete for now or leave as pending.
        // Let's leave as pending to show the "status" flow.

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $finalTotal,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'payment_method' => $request->payment_method,
            'discount_amount' => $discountAmount,
        ]);

        foreach ($orderItemsData as $data) {
            $order->items()->create($data);
        }

        if ($request->payment_method === 'qris') {
            return redirect()->route('orders.payment', $order);
        }

        return redirect()->route('orders.invoice', $order)->with('success', 'Pesanan berhasil! Silakan cetak invoice Anda.');
    }

    public function confirmPayment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Auto-approve as requested
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi! Status pesanan diperbarui.');
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.invoice', $order);
        }
        return view('client.orders.payment', compact('order'));
    }

    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing' // Or completed, depending on flow. Processing is good for paid.
        ]);

        return redirect()->route('orders.invoice', $order)->with('success', 'Pembayaran QRIS Berhasil!');
    }
}
