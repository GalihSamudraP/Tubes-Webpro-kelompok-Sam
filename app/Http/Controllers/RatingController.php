<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductRating;
use App\Models\BaristaRating;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        ProductRating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return back()->with('success', 'Product rating submitted!');
    }

    public function storeBarista(Request $request)
    {
        $request->validate([
            'barista_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        BaristaRating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'barista_id' => $request->barista_id,
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return back()->with('success', 'Barista rating submitted!');
    }
}
