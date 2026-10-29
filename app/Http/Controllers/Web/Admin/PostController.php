<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        // Rows are loaded by the DataTable from admin.data.posts.
        return view('admin.posts.index');
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Post::uniqueSlug($data['slug'] ?: $data['title']);
        $data['author_id'] = $request->user()->id;

        $post = Post::create($data);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Article saved.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Post::uniqueSlug($data['slug'] ?: $data['title'], $post->id);

        $post->update($data);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Article saved.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Article deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'cover_image' => 'nullable|string|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = $data['slug'] ?? '';
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
