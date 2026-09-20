<?php

use App\Http\Controllers\Web\CollectionController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections');
Route::view('/story', 'story')->name('story');
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
    Route::get('/users', [\App\Http\Controllers\Web\AdminController::class, 'users'])->name('admin.users');
});
