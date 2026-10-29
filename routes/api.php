<?php

use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::post('/contact', [\App\Http\Controllers\ContactInquiryController::class, 'store'])->middleware('throttle:5,1');

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    Route::middleware('admin')->group(function () {
        Route::post('/admin/media', [AdminMediaController::class, 'store']);

        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::get('/admin/users/{user}', [AdminUserController::class, 'show']);

        Route::get('/admin/inquiries', [\App\Http\Controllers\ContactInquiryController::class, 'index']);
        Route::patch('/admin/inquiries/{inquiry}/status', [\App\Http\Controllers\ContactInquiryController::class, 'updateStatus']);
        Route::delete('/admin/inquiries/{inquiry}', [\App\Http\Controllers\ContactInquiryController::class, 'destroy']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::patch('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::patch('/admin/orders/{order}', [OrderController::class, 'adminUpdate']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    });
});
