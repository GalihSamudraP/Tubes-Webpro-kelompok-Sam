<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating') // Sort by highest rating
            ->get();

        return view('client.menu', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['ratings.user']);
        $product->loadAvg('ratings', 'rating');
        return view('client.show', compact('product'));
    }
}
