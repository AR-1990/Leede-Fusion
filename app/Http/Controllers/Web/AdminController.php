<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Admin\DataTableController;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Admin pages. Table rows are loaded on demand by the DataTables feeds in Admin\DataTableController,
 * so these methods only pass what the page itself needs (form options, headline stats).
 */
class AdminController extends Controller
{
    /**
     * Show the administrator dashboard.
     */
    public function dashboard(Request $request)
    {
        $totals = Order::query()
            ->selectRaw('COALESCE(SUM(total_amount), 0) as revenue, COUNT(*) as orders, COUNT(DISTINCT user_id) as customers')
            ->first();

        $totalRevenue = (float) $totals->revenue;
        $totalOrders = (int) $totals->orders;
        $uniqueCustomers = (int) $totals->customers;
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

        $recentOrders = Order::query()->with(['items.product', 'user'])->latest()->limit(15)->get();

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
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.products', compact('categories'));
    }

    /**
     * Show categories management.
     */
    public function categories(Request $request)
    {
        return view('admin.categories');
    }

    /**
     * Show order management & tracking.
     */
    public function orders(Request $request)
    {
        return view('admin.orders');
    }

    /**
     * Show customer contact inquiries.
     */
    public function inquiries(Request $request)
    {
        $stats = DataTableController::inquiryStats();

        return view('admin.inquiries', compact('stats'));
    }

    /**
     * Show users list.
     */
    public function users(Request $request)
    {
        return view('admin.users');
    }
}
