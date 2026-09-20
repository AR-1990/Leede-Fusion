<section id="collections" class="section-container" x-data="featuredScroll()">
    <div class="section-header">
        <div class="title-wrapper reveal reveal-fade-up">
            <span class="section-subtitle">SUMMER 2024</span>
            <h2 class="section-title">FEATURED DROPS</h2>
            <p class="section-description">
                Discover our most exclusive retail pieces, curated for elegance and durability.
            </p>
        </div>
        <div class="section-nav reveal reveal-fade-up delay-150">
            <button type="button" class="nav-arrow prev" @click="scroll('left')">←</button>
            <button type="button" class="nav-arrow next" @click="scroll('right')">→</button>
        </div>
    </div>

    <div class="products-grid" x-ref="scroller">
        @forelse($products as $product)
            @include('partials.product-card', [
                'product' => $product,
                'delayClass' => 'delay-' . min(($loop->index % 4 + 1) * 100, 400),
            ])
        @empty
            <p style="padding: 40px; text-align: center; width: 100%; color: #999;">
                No featured products in the catalog yet. Mark products as featured in the Admin Portal.
            </p>
        @endforelse
    </div>
</section>
