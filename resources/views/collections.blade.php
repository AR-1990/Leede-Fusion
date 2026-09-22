@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'Collections | Leede Fusion')

@section('content')
    @include('partials.navbar')

    <div class="collections-header-v2">
        <div class="header-bg">
            <img src="{{ $selectedCategory?->image ?: '/hero-fashion.webp' }}" alt="{{ $selectedCategory ? $selectedCategory->name : 'Collections' }}">
        </div>
        <div class="header-overlay-v2"></div>
        <div class="header-content-v2 reveal reveal-fade-up">
            <h1 class="header-title-v2">{{ $selectedCategory ? strtoupper($selectedCategory->name) : 'COLLECTIONS' }}</h1>
            <p class="header-desc-v2">
                {{ $selectedCategory ? ($selectedCategory->description ?: 'CURATED LUXURY FABRICS & SILHOUETTES') : 'EXPLORE OUR COMPLETE CATALOG OF CONTEMPORARY READY-TO-WEAR & FABRICS' }}
            </p>
        </div>
    </div>

    <div class="collections-layout">
        <aside class="collections-sidebar">
            <div class="sidebar-group">
                <h3 class="sidebar-title">CATEGORIES</h3>
                <ul class="sidebar-list">
                    <li class="{{ empty($categorySlug) ? 'active' : '' }}">
                        <a href="{{ url('/collections') }}">All Pieces ({{ $categories->sum('products_count') }})</a>
                    </li>
                    @foreach($categories as $cat)
                        <li class="{{ $categorySlug === $cat->slug ? 'active' : '' }}">
                            <a href="{{ url('/collections?category=' . urlencode($cat->slug)) }}">
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <main class="collections-main">
            <div class="results-info">
                SHOWING {{ $products->count() }} {{ \Illuminate\Support\Str::plural('PIECE', $products->count()) }}
            </div>

            @if($products->isNotEmpty())
                <div class="collections-grid-v2">
                    @foreach($products as $product)
                        @include('partials.product-card', [
                            'product' => $product,
                            'delayClass' => 'delay-' . min(($loop->index % 3 + 1) * 100, 300),
                        ])
                    @endforeach
                </div>
            @else
                <div class="empty-results">
                    <h3>No items found in this collection</h3>
                    <p style="color: #737373; margin: 15px 0 25px;">Check back soon or explore our other collections.</p>
                    <a href="{{ url('/collections') }}" class="clear-filters">VIEW ALL PIECES</a>
                </div>
            @endif
        </main>
    </div>

    @include('partials.footer')
@endsection
