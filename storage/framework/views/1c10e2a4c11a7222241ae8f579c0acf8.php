<section id="collections" class="section-container featured-editorial-section" x-data="featuredScroll()">
    <!-- Section Header -->
    <div class="section-header">
        <div class="title-wrapper reveal reveal-fade-up">
            <span class="section-subtitle">CURATED DROPS &amp; ATELIER PIECES</span>
            <h2 class="section-title">SIGNATURE READY COLLECTIONS</h2>
            <p class="section-description">
                Handcrafted in limited runs. Available as ready-to-wear pieces or custom-tailored to your exact measurements.
            </p>
        </div>
        <div class="section-nav reveal reveal-fade-up delay-150">
            <button type="button" class="nav-arrow prev" @click="scroll('left')" aria-label="Previous products">←</button>
            <button type="button" class="nav-arrow next" @click="scroll('right')" aria-label="Next products">→</button>
        </div>
    </div>

    <!-- Atelier Bespoke Sizing Ribbon Banner -->
    <div class="atelier-product-ribbon reveal reveal-fade-up delay-100">
        <div class="ribbon-content">
            <span class="ribbon-badge">BESPOKE OPTION</span>
            <span class="ribbon-text">Every design below can be customized to your precise height, sleeve, &amp; body measurements.</span>
        </div>
        <a href="<?php echo e(route('contact')); ?>?inquiry=Custom+Stitching" class="ribbon-link">
            <span>REQUEST CUSTOM FIT &rarr;</span>
        </a>
    </div>

    <!-- Products Scroll Grid -->
    <div class="products-grid" x-ref="scroller">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('partials.product-card', [
                'product' => $product,
                'delayClass' => 'delay-' . min(($loop->index % 4 + 1) * 100, 400),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="padding: 40px; text-align: center; width: 100%; color: #999;">
                No featured products in the catalog yet. Mark products as featured in the Admin Portal.
            </p>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/featured-products.blade.php ENDPATH**/ ?>