<?php

use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
/**
 * When APP_DEBUG=true: open GET /api/db-check in the browser (same origin as your Next app → /api/db-check).
 * Compare `laravel_database`, `host`, and `port` with the MySQL server phpMyAdmin is connected to.
 * If they differ, new users/products save to one server while you browse another — that is why rows “disappear”.
 */
Route::get('/db-check', function () {
    if (! config('app.debug')) {
        return response()->json([
            'message' => 'Set APP_DEBUG=true temporarily to use /api/db-check, then set it back to false.',
        ], 404);
    }

    try {
        $conn = config('database.default');
        $cfg = config("database.connections.{$conn}");

        return response()->json([
            'laravel_connection' => $conn,
            'laravel_database' => DB::connection()->getDatabaseName(),
            'driver' => DB::connection()->getDriverName(),
            'host' => $cfg['host'] ?? null,
            'port' => $cfg['port'] ?? null,
            'socket' => $cfg['unix_socket'] ?: null,
            'users_count' => User::query()->count(),
            'last_5_users' => User::query()->orderByDesc('id')->limit(5)->get(['id', 'email', 'created_at']),
            'hint' => 'phpMyAdmin must use the SAME host/port (or socket) as above. XAMPP/MAMP often use a different MySQL than Homebrew on 127.0.0.1:3306.',
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
        ], 500);
    }
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

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
