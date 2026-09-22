<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_featured', true)
            ->latest()
            ->limit(12)
            ->get();

        $latestProducts = Product::query()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'categories' => $categories,
            'heroSlides' => config('content.hero_slides'),
            'whyShop' => config('content.why_shop'),
            'project' => config('content.project'),
            'blogPosts' => config('content.blog_posts', []),
        ]);
    }
}
