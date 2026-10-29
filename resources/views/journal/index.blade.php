@extends('layouts.app')

@section('title', ($category ? $category.' | ' : '').'The Leede Journal: Fabric, Styling & Bespoke Guides | Leede Fusion')
@section('description', 'Fabric masterclasses, styling guides and bespoke tailoring stories from Leede Fusion, a Karachi design house.')
@if ($posts->currentPage() > 1)
    @section('canonical', $posts->url($posts->currentPage()))
@endif

@section('content')
    @include('partials.navbar')

    <div class="the-house-page">
        <header class="house-hero">
            <div class="house-hero-inner reveal reveal-fade-up">
                <span class="house-page-badge">EDITORIAL & INSIGHTS</span>
                <h1 class="house-main-title">THE JOURNAL</h1>
                <p class="house-main-subtitle">
                    Couture stories, fabric masterclasses and styling guides from our Karachi design house.
                </p>
            </div>
        </header>

        <main class="journal-container">
            @if ($categories->isNotEmpty())
                <nav class="journal-filters reveal reveal-fade-up" aria-label="Journal categories">
                    <a href="{{ route('journal') }}" class="{{ $category ? '' : 'is-active' }}">All</a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('journal', ['category' => $cat]) }}" class="{{ $category === $cat ? 'is-active' : '' }}">{{ $cat }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($posts->isEmpty())
                <div class="empty-results">
                    <h3 style="font-family: var(--font-heading); font-size: 28px; margin-bottom: 12px;">NO ARTICLES YET</h3>
                    <p style="color: #737373;">New stories are on their way.</p>
                </div>
            @else
                <div class="blog-grid">
                    @foreach ($posts as $post)
                        @include('partials.post-card', ['post' => $post, 'index' => $loop->index])
                    @endforeach
                </div>

                <div class="journal-pagination">{{ $posts->links() }}</div>
            @endif
        </main>
    </div>

    @include('partials.footer')
@endsection
