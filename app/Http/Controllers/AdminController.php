<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Promo;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_sales' => Order::sum('total_price'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_users' => User::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    // --- Menu Management ---
    public function menuIndex()
    {
        $products = Product::all();
        return view('admin.menu.index', compact('products'));
    }

    public function menuStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|url',
        ]);

        Product::create($validated);
        return redirect()->back()->with('success', 'Menu added successfully');
    }

    public function menuDestroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Menu deleted successfully');
    }

    // --- Promo Management ---
    public function promoIndex()
    {
        $promos = Promo::all();
        return view('admin.promos.index', compact('promos'));
    }

    public function promoStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:promos',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Promo::create($validated);
        return redirect()->back()->with('success', 'Promo created successfully');
    }

    // --- User/Account Management ---
    public function userIndex()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
}
