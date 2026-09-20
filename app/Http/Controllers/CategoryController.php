<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Public: list categories (with product counts).
     */
    public function index()
    {
        return response()->json(
            Category::query()
                ->withCount('products')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Public: single category with its products.
     */
    public function show(Category $category)
    {
        return response()->json(
            $category->load(['products' => fn ($q) => $q->orderByDesc('id')])
        );
    }

    /**
     * Admin: create category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer|min:0|max:999999',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']);
        $slug = $this->uniqueSlug($slug);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return response()->json($category->loadCount('products'), 201);
    }

    /**
     * Admin: update category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$category->id,
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer|min:0|max:999999',
        ]);

        if (array_key_exists('slug', $validated) && filled($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $category->id);
        } elseif (array_key_exists('name', $validated)) {
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['name']), $category->id);
        }

        $category->update($validated);

        return response()->json($category->fresh()->loadCount('products'));
    }

    /**
     * Admin: delete category (only if no products, to avoid accidental cascades).
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'This category still has products. Delete or move them first.',
            ], 409);
        }

        $category->delete();

        return response()->json(null, 204);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : Str::random(8);
        $original = $slug;
        $i = 1;

        while (Category::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
