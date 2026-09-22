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