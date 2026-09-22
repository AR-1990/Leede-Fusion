<section id="categories" class="section-container categories-section">
    <div class="reveal reveal-fade-up">
        <h2 class="section-title">SHOP BY CATEGORY</h2>
        <p class="section-description">
            Explore our curated collections of premium men's fabrics, women's suits, and stylish accessories — managed from your admin catalog.
        </p>
    </div>

    <div class="categories-grid">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(url('/collections?category=' . urlencode($cat->slug))); ?>" class="category-card reveal reveal-scale-up delay-<?php echo e(min(($loop->index % 3 + 1) * 100, 300)); ?>">
                <div class="category-img-wrapper" x-data="imageLoader()">
                    <div class="skeleton skeleton-img" x-show="!loaded" x-transition.opacity.duration.400ms></div>
                    <img
                        src="<?php echo e($cat->image ?: '/hero-fashion.webp'); ?>"
                        alt="<?php echo e($cat->name); ?>"
                        class="category-img"
                        :class="{ 'img-loaded': loaded }"
                        @load="onLoad()"
                        loading="lazy"
                    >
                    <div class="category-overlay">
                        <div class="category-content">
                            <h3 class="category-title"><?php echo e($cat->name); ?></h3>
                            <p class="category-desc"><?php echo e($cat->description ?: 'Explore this collection.'); ?></p>
                            <span class="explore-link">Explore Collection →</span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="text-align: center; color: #999; grid-column: 1 / -1;">
                No categories yet. Add them in the admin portal under Categories.
            </p>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/categories.blade.php ENDPATH**/ ?>