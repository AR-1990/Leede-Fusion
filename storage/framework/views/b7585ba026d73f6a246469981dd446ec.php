<section class="why-shop">
    <div class="why-shop-grid">
        <div class="why-shop-info reveal reveal-fade-up">
            <h2 class="section-title">WHY SHOP WITH US?</h2>
            <p class="section-description">
                We've got you covered with hassle-free shopping, top-tier service, and guarantees
                that keep you confident in every purchase.
            </p>
        </div>
        <?php $__currentLoopData = $whyShop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="why-shop-item reveal reveal-fade-up delay-<?php echo e(min(($loop->index + 1) * 100, 400)); ?>">
                <h3 class="why-shop-title"><?php echo e($item['title']); ?></h3>
                <p class="why-shop-desc"><?php echo e($item['description']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/why-shop.blade.php ENDPATH**/ ?>