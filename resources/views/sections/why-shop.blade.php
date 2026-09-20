<section class="why-shop">
    <div class="why-shop-grid">
        <div class="why-shop-info reveal reveal-fade-up">
            <h2 class="section-title">WHY SHOP WITH US?</h2>
            <p class="section-description">
                We've got you covered with hassle-free shopping, top-tier service, and guarantees
                that keep you confident in every purchase.
            </p>
        </div>
        @foreach($whyShop as $item)
            <div class="why-shop-item reveal reveal-fade-up delay-{{ min(($loop->index + 1) * 100, 400) }}">
                <h3 class="why-shop-title">{{ $item['title'] }}</h3>
                <p class="why-shop-desc">{{ $item['description'] }}</p>
            </div>
        @endforeach
    </div>
</section>
