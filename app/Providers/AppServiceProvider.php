<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.navbar', function ($view) {
            $navCategories = [];

            try {
                if (Schema::hasTable('categories')) {
                    $navCategories = Category::query()
                        ->withCount('products')
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get()
                        ->map(static fn (Category $category): array => [
                            'name' => $category->name,
                            'slug' => $category->slug,
                            'url' => url('/collections?category='.urlencode($category->slug)),
                            'description' => Str::limit((string) $category->description, 56),
                            'count' => (int) $category->products_count,
                        ])
                        ->values()
                        ->all();
                }
            } catch (\Throwable $e) {
                $navCategories = [];
            }

            $view->with('navCategories', $navCategories);
        });

        View::composer('layouts.admin', function ($view) {
            $unreadCount = 0;
            try {
                if (Schema::hasTable('contact_inquiries')) {
                    $unreadCount = ContactInquiry::query()->where('status', 'unread')->count();
                }
            } catch (\Throwable $e) {
                $unreadCount = 0;
            }
            $view->with('unreadInquiriesCount', $unreadCount);
        });
    }
}
