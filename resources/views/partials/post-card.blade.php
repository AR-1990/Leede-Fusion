@php($delay = (($index ?? 0) % 3 + 1) * 100)
<a href="{{ route('journal.show', $post) }}" class="blog-card blog-card-link reveal reveal-scale-up delay-{{ $delay }}">
    <div class="blog-card-media" x-data="imageLoader()">
        <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
        <img src="{{ $post->cover_image ?: '/hero-fashion.webp' }}"
             alt="{{ $post->title }}"
             class="blog-card-img"
             :class="{ 'img-loaded': loaded }"
             @load="onLoad()"
             loading="lazy">
        @if ($post->category)
            <span class="blog-category-badge">{{ $post->category }}</span>
        @endif
    </div>

    <div class="blog-card-content">
        <div class="blog-meta-row">
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('F d, Y') }}</time>
            <span class="blog-meta-dot"></span>
            <span>{{ $post->read_time }}</span>
        </div>

        <h3 class="blog-card-title">{{ $post->title }}</h3>
        <p class="blog-card-excerpt">{{ $post->summary }}</p>

        <div class="blog-card-cta">
            <span>Read Article</span>
            <span>&rarr;</span>
        </div>
    </div>
</a>
