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
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('partials.product-card', [
                'product' => $product,
                'delayClass' => 'delay-' . min(($loop->index % 4 + 1) * 100, 400),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="text-align: center; color: #999; margin-bottom: 24px; grid-column: 1 / -1;">
                No products in the database yet. Add products from the Admin Portal.
            </p>
        <?php endif; ?>
    </div>

    <div class="load-more-wrapper reveal reveal-fade-up delay-200">
        <a href="<?php echo e(url('/collections')); ?>" class="load-more-btn">
            SEE ALL FABRICS <span>→</span>
        </a>
    </div>
</section>
<?php /**PATH /Users/mac/Documents/GitHub/Leede-Fusion/resources/views/sections/new-drops.blade.php ENDPATH**/ ?>