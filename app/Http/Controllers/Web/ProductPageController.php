<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductPageController extends Controller
{
    public function show(Product $product): View
    {
        $product->load('category');

        $related = Product::query()
            ->with('category')
            ->where('id', '!=', $product->id)
            ->orderByRaw('category_id = ? desc', [$product->category_id])
            ->latest()
            ->limit(4)
            ->get();

        return view('product', compact('product', 'related'));
    }
}
