<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * List registered customers (and staff) for the vendor dashboard.
     */
    public function index(Request $request)
    {
        $perPage = min($request->integer('per_page', 50), 100);

        return response()->json(
            User::query()
                ->select(['id', 'name', 'email', 'is_admin', 'email_verified_at', 'created_at', 'updated_at'])
                ->orderByDesc('id')
                ->paginate($perPage)
        );
    }

    /**
     * Show a single user (no password fields).
     */
    public function show(User $user)
    {
        return response()->json(
            $user->only(['id', 'name', 'email', 'is_admin', 'email_verified_at', 'created_at', 'updated_at'])
        );
    }
}
