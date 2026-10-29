<?php

use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\Web\Admin\PostController as AdminPostController;
use App\Http\Controllers\Web\Admin\CustomOrderController as AdminCustomOrderController;
use App\Http\Controllers\Web\Admin\DataTableController as AdminDataTableController;
use App\Http\Controllers\Web\Admin\DesignOptionController as AdminDesignOptionController;
use App\Http\Controllers\Web\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\CollectionController;
use App\Http\Controllers\Web\DesignStudioController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\JournalController;
use App\Http\Controllers\Web\ProductPageController;
use App\Http\Controllers\Web\SitemapController;
use App\Http\Controllers\Web\TrackController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections');
Route::get('/collections/{product:slug}', [ProductPageController::class, 'show'])->name('product.show');
Route::get('/design', [DesignStudioController::class, 'create'])->name('design');
Route::post('/design', [DesignStudioController::class, 'store'])->middleware('throttle:6,1')->name('design.store');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:6,1')->name('checkout');
Route::get('/track', [TrackController::class, 'form'])->name('track');
Route::post('/track', [TrackController::class, 'lookup'])->middleware('throttle:10,1')->name('track.lookup');
Route::get('/track/{code}', [TrackController::class, 'show'])->name('track.show');
Route::view('/story', 'story')->name('story');
Route::view('/the-house', 'story')->name('the-house');
Route::view('/atelier', 'atelier')->name('atelier');
Route::view('/the-atelier', 'atelier')->name('the-atelier');
Route::view('/contact', 'contact')->name('contact');
Route::view('/the-process', 'process')->name('the-process');
Route::view('/the-fit', 'fit')->name('the-fit');
Route::get('/journal', [JournalController::class, 'index'])->name('journal');
Route::get('/journal/{post}', [JournalController::class, 'show'])->name('journal.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::post('/contact', [\App\Http\Controllers\ContactInquiryController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::view('/cart', 'cart')->name('cart');
Route::view('/login', 'login')->name('login');
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
});
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin Protected Routes
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [\App\Http\Controllers\Web\AdminController::class, 'products'])->name('admin.products');
    Route::get('/categories', [\App\Http\Controllers\Web\AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/orders', [\App\Http\Controllers\Web\AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/inquiries', [\App\Http\Controllers\Web\AdminController::class, 'inquiries'])->name('admin.inquiries');
    Route::get('/users', [\App\Http\Controllers\Web\AdminController::class, 'users'])->name('admin.users');

    Route::resource('posts', AdminPostController::class)->except('show')->names('admin.posts');
    // Session-authenticated upload used by the journal editor (inline images + cover).
    Route::post('/media', [AdminMediaController::class, 'store'])->name('admin.media');

    Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('admin.settings');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');

    Route::get('/studio-options', [AdminDesignOptionController::class, 'index'])->name('admin.design-options.index');
    Route::post('/studio-options', [AdminDesignOptionController::class, 'store'])->name('admin.design-options.store');
    Route::put('/studio-options/{designOption}', [AdminDesignOptionController::class, 'update'])->name('admin.design-options.update');
    Route::delete('/studio-options/{designOption}', [AdminDesignOptionController::class, 'destroy'])->name('admin.design-options.destroy');

    // Server-side DataTables feeds (Yajra) and single-record JSON for row actions.
    Route::prefix('data')->name('admin.data.')->controller(AdminDataTableController::class)->group(function () {
        Route::get('/products', 'products')->name('products');
        Route::get('/categories', 'categories')->name('categories');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/inquiries', 'inquiries')->name('inquiries');
        Route::get('/users', 'users')->name('users');
        Route::get('/posts', 'posts')->name('posts');
        Route::get('/custom-orders', 'customOrders')->name('custom-orders');
        Route::get('/{type}/{id}', 'record')->whereIn('type', ['product', 'category', 'order', 'inquiry'])->whereNumber('id')->name('record');
    });

    Route::get('/custom-orders', [AdminCustomOrderController::class, 'index'])->name('admin.custom-orders.index');
    Route::get('/custom-orders/{customOrder}', [AdminCustomOrderController::class, 'show'])->name('admin.custom-orders.show');
    Route::put('/custom-orders/{customOrder}', [AdminCustomOrderController::class, 'update'])->name('admin.custom-orders.update');
});

Route::get('/robots.txt', fn () => response(
    "User-agent: *\nDisallow: /admin\nDisallow: /cart\nDisallow: /login\n\nSitemap: ".route('sitemap')."\n"
)->header('Content-Type', 'text/plain'))->name('robots');
