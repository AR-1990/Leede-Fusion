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

    <div class="collections-layout collections-layout-products-only">
        <main class="collections-main">
            <section class="collections-filters">
                <div class="collections-filters-head">
                    <h2 class="collections-filters-title">Filter by Category</h2>
                    <p class="collections-filters-copy">Choose a category to see only its products.</p>
                </div>

                <div class="collections-filters-grid">
                    <a href="{{ url('/collections') }}" class="collection-filter-card {{ empty($categorySlug) ? 'is-active' : '' }}">
                        <span class="collection-filter-name">All Pieces</span>
                        <span class="collection-filter-meta">{{ $categories->sum('products_count') }} pieces</span>
                    </a>

                    @foreach($categories as $cat)
                        <a href="{{ url('/collections?category=' . urlencode($cat->slug)) }}" class="collection-filter-card {{ $categorySlug === $cat->slug ? 'is-active' : '' }}">
                            <span class="collection-filter-name">{{ $cat->name }}</span>
                            <span class="collection-filter-meta">{{ $cat->products_count }} {{ \Illuminate\Support\Str::plural('piece', $cat->products_count) }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            <div class="collections-toolbar">
                <div class="results-info">
                    SHOWING {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} OF {{ $products->total() }} {{ \Illuminate\Support\Str::plural('PIECE', $products->total()) }}
                    @if($selectedCategory)
                        IN {{ strtoupper($selectedCategory->name) }}
                    @endif
                </div>

                @if($selectedCategory)
                    <a href="{{ url('/collections') }}" class="collections-reset-link">View All Pieces</a>
                @endif
            </div>

            @if($products->isNotEmpty())
                <div class="collections-grid-v2">
                    @foreach($products as $product)
                        @include('partials.product-card', [
                            'product' => $product,
                            'delayClass' => 'delay-' . min(($loop->index % 4 + 1) * 100, 400),
                        ])
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div class="collections-pagination">
                        @foreach($products->onEachSide(1)->linkCollection() as $link)
                            @if($link['url'])
                                <a href="{{ $link['url'] }}" class="collections-pagination-link {{ $link['active'] ? 'is-active' : '' }}">
                                    {!! $link['label'] !!}
                                </a>
                            @else
                                <span class="collections-pagination-link is-disabled">{!! $link['label'] !!}</span>
                            @endif
                        @endforeach
                    </div>
                @endif
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
