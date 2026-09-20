<section id="categories" class="section-container categories-section">
    <div class="reveal reveal-fade-up">
        <h2 class="section-title">SHOP BY CATEGORY</h2>
        <p class="section-description">
            Explore our curated collections of premium men's fabrics, women's suits, and stylish accessories — managed from your admin catalog.
        </p>
    </div>

    <div class="categories-grid">
        @forelse($categories as $cat)
            <a href="{{ url('/collections?category=' . urlencode($cat->slug)) }}" class="category-card reveal reveal-scale-up delay-{{ min(($loop->index % 3 + 1) * 100, 300) }}">
                <div class="category-img-wrapper" x-data="imageLoader()">
                    <div class="skeleton skeleton-img" x-show="!loaded" x-transition.opacity.duration.400ms></div>
                    <img
                        src="{{ $cat->image ?: '/hero-fashion.webp' }}"
                        alt="{{ $cat->name }}"
                        class="category-img"
                        :class="{ 'img-loaded': loaded }"
                        @load="onLoad()"
                        loading="lazy"
                    >
                    <div class="category-overlay">
                        <div class="category-content">
                            <h3 class="category-title">{{ $cat->name }}</h3>
                            <p class="category-desc">{{ $cat->description ?: 'Explore this collection.' }}</p>
                            <span class="explore-link">Explore Collection →</span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p style="text-align: center; color: #999; grid-column: 1 / -1;">
                No categories yet. Add them in the admin portal under Categories.
            </p>
        @endforelse
    </div>
</section>
