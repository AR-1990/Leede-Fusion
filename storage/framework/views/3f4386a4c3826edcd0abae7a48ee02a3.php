<section class="product-focus-single">
    <div class="focus-grid">
        <div class="focus-text-content reveal reveal-slide-left">
            <span class="focus-badge">FEATURED DROP</span>
            <h2 class="focus-title">NIGHTFALL OVERSIZED HOODIE</h2>
            <p class="focus-desc">
                The ultimate street silhouette. Crafted from 450GSM premium fleece
                with a relaxed fit and dropped shoulders. Designed in Karachi,
                built for the global rebellion.
            </p>
            <div class="focus-price-tag">
                Rs. 8,500 <span class="old-price">Rs. 12,000</span>
            </div>
            <a href="<?php echo e(url('/collections')); ?>" class="shop-now-btn white-btn">
                Buy Now <span>→</span>
            </a>
        </div>
        <div class="focus-image-content reveal reveal-slide-right delay-200" x-data="imageLoader()">
            <div class="skeleton skeleton-dark skeleton-img" x-show="!loaded" x-transition.opacity.duration.400ms></div>
            <img
                src="/nd1.jpg"
                alt="Nightfall Hoodie"
                class="focus-single-img"
                :class="{ 'img-loaded': loaded }"
                @load="onLoad()"
                loading="lazy"
            >
        </div>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/product-focus.blade.php ENDPATH**/ ?>