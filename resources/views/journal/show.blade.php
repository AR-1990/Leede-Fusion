@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title).' | Leede Fusion Journal')
@section('description', $post->meta_description ?: $post->summary)
@section('og_type', 'article')
@section('og_image', $post->cover_image ?: '/hero-fashion.webp')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->meta_description ?: $post->summary,
            'image' => url($post->cover_image ?: '/hero-fashion.webp'),
            'datePublished' => $post->published_at?->toAtomString(),
            'dateModified' => $post->updated_at?->toAtomString(),
            'mainEntityOfPage' => route('journal.show', $post),
            'author' => ['@type' => 'Organization', 'name' => 'Leede Fusion'],
            'publisher' => ['@type' => 'Organization', 'name' => 'Leede Fusion', 'logo' => ['@type' => 'ImageObject', 'url' => asset('Logo.png')]],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    @include('partials.navbar')

    <article class="journal-article">
        <header class="journal-article-header reveal reveal-fade-up">
            <nav class="journal-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a> / <a href="{{ route('journal') }}">Journal</a>
                @if ($post->category)
                    / <a href="{{ route('journal', ['category' => $post->category]) }}">{{ $post->category }}</a>
                @endif
            </nav>
            <h1 class="journal-article-title">{{ $post->title }}</h1>
            <div class="blog-meta-row journal-article-meta">
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('F d, Y') }}</time>
                <span class="blog-meta-dot"></span>
                <span>{{ $post->read_time }}</span>
            </div>
        </header>

        @if ($post->cover_image)
            <figure class="journal-article-cover reveal reveal-fade-up delay-100">
                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}">
            </figure>
        @endif

        <div class="journal-prose">
            {!! $post->body !!}
        </div>

        <div class="journal-article-cta">
            <p>Love this look? We can make it for you, ready-to-wear or stitched to your measurements.</p>
            <div class="journal-article-cta-actions">
                <a href="{{ config('content.project.whatsapp_link') }}?text={{ rawurlencode('Hi Leede Fusion, I read your article "'.$post->title.'" and would like to enquire.') }}"
                   target="_blank" rel="noopener noreferrer" class="boutique-wa-btn" style="width: auto; margin-top: 0; padding: 12px 22px;">
                    Enquire on WhatsApp
                </a>
                <a href="{{ route('design') }}" class="house-primary-cta-btn">START YOUR DESIGN</a>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="blog-section">
            <div class="blog-header-wrapper reveal reveal-fade-up">
                <span class="blog-kicker">KEEP READING</span>
                <h2 class="section-title">MORE FROM THE JOURNAL</h2>
            </div>
            <div class="blog-grid">
                @foreach ($related as $item)
                    @include('partials.post-card', ['post' => $item, 'index' => $loop->index])
                @endforeach
            </div>
        </section>
    @endif

    @include('partials.footer')
@endsection
