<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $posts = Post::query()->published()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->paginate(9)
            ->withQueryString();

        $categories = Post::query()->published()->reorder()
            ->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('journal.index', compact('posts', 'categories', 'category'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->isLive(), 404);

        $related = Post::query()->published()
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return view('journal.show', compact('post', 'related'));
    }
}
