<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect(['home', 'collections', 'design', 'the-house', 'the-atelier', 'the-process', 'the-fit', 'journal', 'contact'])
            ->map(fn ($name) => ['loc' => route($name), 'lastmod' => null]);

        Product::query()->get(['id', 'slug', 'updated_at'])->each(fn ($p) => $urls->push([
            'loc' => route('product.show', $p),
            'lastmod' => $p->updated_at,
        ]));

        Category::query()->get()->each(fn ($c) => $urls->push([
            'loc' => route('collections', ['category' => $c->slug]),
            'lastmod' => $c->updated_at,
        ]));

        Post::query()->published()->get()->each(fn ($p) => $urls->push([
            'loc' => route('journal.show', $p),
            'lastmod' => $p->updated_at,
        ]));

        return response()->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
