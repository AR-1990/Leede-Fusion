<section id="all-collections" class="section-container">
    <div class="section-header">
        <div class="title-wrapper reveal reveal-fade-up">
            <h2 class="section-title">ALL COLLECTIONS</h2>
            <p class="section-description">
                Explore our complete range — synced from your store catalog.
            </p>
        </div>
    </div>

    <div class="new-drops-static-grid">
        @forelse($products as $product)
            @include('partials.product-card', [
                'product' => $product,
                'delayClass' => 'delay-' . min(($loop->index % 4 + 1) * 100, 400),
            ])
        @empty
            <p style="text-align: center; color: #999; margin-bottom: 24px; grid-column: 1 / -1;">
                No products in the database yet. Add products from the Admin Portal.
            </p>
        @endforelse
    </div>

    <div class="load-more-wrapper reveal reveal-fade-up delay-200">
        <a href="{{ url('/collections') }}" class="load-more-btn">
            SEE ALL FABRICS <span>→</span>
        </a>
    </div>
</section>
