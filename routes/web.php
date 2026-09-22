<?php

use App\Http\Controllers\Web\CollectionController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections');
Route::view('/story', 'story')->name('story');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactInquiryController::class, 'store'])->name('contact.store');
Route::view('/cart', 'cart')->name('cart');
Route::view('/login', 'login')->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin Protected Routes
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [\App\Http\Controllers\Web\AdminController::class, 'products'])->name('admin.products');
    Route::get('/categories', [\App\Http\Controllers\Web\AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/orders', [\App\Http\Controllers\Web\AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/inquiries', [\App\Http\Controllers\Web\AdminController::class, 'inquiries'])->name('admin.inquiries');
    Route::get('/users', [\App\Http\Controllers\Web\AdminController::class, 'users'])->name('admin.users');
});
