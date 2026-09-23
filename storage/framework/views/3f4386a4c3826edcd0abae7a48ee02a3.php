<section class="product-focus-single">
    <div class="focus-grid">
        <div class="focus-text-content reveal reveal-slide-left">
            <span class="focus-badge">ATELIER MASTERPIECE</span>
            <h2 class="focus-title">NOOR-E-ZAHRA BESPOKE SILK PRET</h2>
            <p class="focus-desc">
                An embodiment of modest luxury. Handcrafted from pure 80-count jacquard lawn with delicate resham border embroidery and customized silhouette tailored to your exact height and body form.
            </p>
            
            <div class="focus-features-pills">
                <span class="focus-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Made-to-Measure Custom Sizing
                </span>
                <span class="focus-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Pure Silk &amp; Hand-Embroidery
                </span>
                <span class="focus-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Express 5-Day Atelier Stitching
                </span>
            </div>

            <div class="focus-price-tag">
                Rs. 9,850 <span class="old-price">Rs. 14,500</span>
            </div>

            <div class="focus-action-btns">
                <a href="<?php echo e(route('contact')); ?>?inquiry=Custom+Stitching" class="shop-now-btn white-btn">
                    ORDER BESPOKE TAILORED <span>→</span>
                </a>
                <a href="<?php echo e(url('/collections')); ?>" class="shop-now-btn outline-btn" style="border: 1px solid rgba(255,255,255,0.4); color: #fff; background: transparent;">
                    EXPLORE COLLECTION
                </a>
            </div>
        </div>
        <div class="focus-image-content reveal reveal-slide-right delay-200" x-data="imageLoader()">
            <div class="skeleton skeleton-dark skeleton-img" x-show="!loaded" x-transition.opacity.duration.400ms></div>
            <img
                src="/1.jpg"
                alt="Noor-e-Zahra Bespoke Silk Pret"
                class="focus-single-img"
                :class="{ 'img-loaded': loaded }"
                @load="onLoad()"
                loading="lazy"
            >
        </div>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/product-focus.blade.php ENDPATH**/ ?>