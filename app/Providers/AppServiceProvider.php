<?php

namespace App\Providers;

use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
