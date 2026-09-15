<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)
            ->orderByRaw("CASE category WHEN 'espresso' THEN 1 WHEN 'manual_brew' THEN 2 WHEN 'non_coffee' THEN 3 WHEN 'pastry' THEN 4 ELSE 5 END")
            ->get()
            ->groupBy('category');

        return view('shop.index', compact('products'));
    }
}
