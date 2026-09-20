<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Public catalog (optional filters).
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('category_slug')) {
            $slug = $request->string('category_slug')->toString();
            $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        return response()->json($query->orderByDesc('id')->get());
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|string|max:50',
            'description' => 'nullable|string',
            'old_price' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:2048',
            'images' => 'nullable|array|max:30',
            'images.*' => 'string|max:2048',
            'tag' => 'nullable|string|max:100',
            'is_featured' => 'sometimes|boolean',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'price' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
            'old_price' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:2048',
            'images' => 'nullable|array|max:30',
            'images.*' => 'string|max:2048',
            'tag' => 'nullable|string|max:100',
            'is_featured' => 'sometimes|boolean',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json($product->fresh()->load('category'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
