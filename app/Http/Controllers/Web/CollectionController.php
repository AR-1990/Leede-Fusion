<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');

        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = $categories->firstWhere('slug', $categorySlug);
        }

        $query = Product::query()->with('category');

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }

        $products = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('collections', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $selectedCategory,
            'categorySlug' => $categorySlug,
        ]);
    }
}
