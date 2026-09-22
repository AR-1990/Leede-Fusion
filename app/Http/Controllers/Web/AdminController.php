<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactInquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the administrator dashboard.
     */
    public function dashboard(Request $request)
    {
        $orders = Order::query()->with(['items.product', 'user'])->latest()->get();
        
        $totalRevenue = (float) $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $uniqueCustomers = $orders->pluck('user_id')->filter()->unique()->count();
        if ($uniqueCustomers === 0) {
            $uniqueCustomers = User::query()->where('is_admin', false)->count();
        }
        $avgOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        $stats = [
            ['label' => 'TOTAL REVENUE', 'value' => 'Rs. ' . number_format($totalRevenue)],
            ['label' => 'TOTAL ORDERS', 'value' => (string) $totalOrders],
            ['label' => 'TOTAL CUSTOMERS', 'value' => (string) $uniqueCustomers],
            ['label' => 'AVG ORDER VALUE', 'value' => 'Rs. ' . number_format($avgOrderValue)],
        ];

        $recentOrders = $orders->take(15);

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders'
        ));
    }

    /**
     * Show products catalog management.
     */
    public function products(Request $request)
    {
        $products = Product::query()->with('category')->latest()->get();
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products', compact('products', 'categories'));
    }

    /**
     * Show categories management.
     */
    public function categories(Request $request)
    {
        $categories = Category::query()->withCount('products')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.categories', compact('categories'));
    }

    /**
     * Show order management & tracking.
     */
    public function orders(Request $request)
    {
        $orders = Order::query()->with(['items.product', 'user'])->latest()->get();

        return view('admin.orders', compact('orders'));
    }

    /**
     * Show customer contact inquiries.
     */
    public function inquiries(Request $request)
    {
        $inquiries = ContactInquiry::query()->latestFirst()->get();

        $stats = [
            'total' => $inquiries->count(),
            'unread' => $inquiries->where('status', 'unread')->count(),
            'responded' => $inquiries->where('status', 'responded')->count(),
            'today' => $inquiries->filter(fn ($i) => $i->created_at && $i->created_at->isToday())->count(),
        ];

        return view('admin.inquiries', compact('inquiries', 'stats'));
    }

    /**
     * Show users list.
     */
    public function users(Request $request)
    {
        $users = User::query()->orderByDesc('id')->paginate(50);

        return view('admin.users', compact('users'));
    }
}
