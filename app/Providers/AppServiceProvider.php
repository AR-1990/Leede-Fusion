<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ContactInquiry;
use App\Models\Setting;
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
        $this->applySiteSettings();

        View::composer(['partials.navbar', 'partials.footer'], function ($view) {
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

            try {
                $view->with('newCustomOrdersCount', \App\Models\CustomOrder::query()->where('stage', 'received')->count());
            } catch (\Throwable $e) {
                $view->with('newCustomOrdersCount', 0);
            }
        });
    }

    /**
     * Settings saved from Admin → Site Settings override config/content.php,
     * so every config('content.*') call in the views stays dynamic.
     */
    private function applySiteSettings(): void
    {
        try {
            $settings = Setting::allCached();
        } catch (\Throwable $e) {
            return; // table not migrated yet
        }

        if (! empty($settings['project']) && is_array($settings['project'])) {
            $project = array_merge(config('content.project', []), array_filter($settings['project'], fn ($v) => $v !== null));
            $intl = preg_replace('/\D/', '', (string) ($project['whatsapp_international'] ?? ''));
            $project['whatsapp_international'] = $intl;
            $project['whatsapp_link'] = 'https://wa.me/'.$intl;
            config(['content.project' => $project]);
        }

        foreach (['hero_slides', 'why_shop'] as $key) {
            if (! empty($settings[$key]) && is_array($settings[$key])) {
                config(["content.{$key}" => array_values($settings[$key])]);
            }
        }
    }
}
