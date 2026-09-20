<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMediaController extends Controller
{
    /**
     * Store an image on the public disk (served via /storage/... after `php artisan storage:link`).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'folder' => 'nullable|string|in:categories,products,gallery',
        ]);

        $folder = $validated['folder'] ?? 'products';
        $path = $request->file('file')->store("uploads/{$folder}", 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'url' => $url,
            'path' => $path,
        ], 201);
    }
}
