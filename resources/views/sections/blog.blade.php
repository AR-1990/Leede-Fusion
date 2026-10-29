@if (count($blogPosts ?? []) > 0)
<section id="journal" class="blog-section">
    <!-- Section Header -->
    <div class="blog-header-wrapper reveal reveal-fade-up">
        <span class="blog-kicker">EDITORIAL & INSIGHTS</span>
        <h2 class="section-title">THE LEEDE JOURNAL</h2>
        <p class="section-description">
            Couture stories, fabric masterclasses, and contemporary styling guides from our Karachi design house.
        </p>
    </div>

    <!-- Blog Posts Grid -->
    <div class="blog-grid">
        @foreach ($blogPosts as $post)
            @include('partials.post-card', ['post' => $post, 'index' => $loop->index])
        @endforeach
    </div>

    <div class="journal-more reveal reveal-fade-up">
        <a href="{{ route('journal') }}" class="house-primary-cta-btn">READ THE JOURNAL</a>
    </div>
</section>
@endif
