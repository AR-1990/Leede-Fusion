<?php $__env->startSection('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'Collections | Leede'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="collections-header-v2">
        <div class="header-bg">
            <img src="<?php echo e($selectedCategory?->image ?: '/hero-fashion.webp'); ?>" alt="<?php echo e($selectedCategory ? $selectedCategory->name : 'Collections'); ?>">
        </div>
        <div class="header-overlay-v2"></div>
        <div class="header-content-v2 reveal reveal-fade-up">
            <h1 class="header-title-v2"><?php echo e($selectedCategory ? strtoupper($selectedCategory->name) : 'COLLECTIONS'); ?></h1>
            <p class="header-desc-v2">
                <?php echo e($selectedCategory ? ($selectedCategory->description ?: 'CURATED LUXURY FABRICS & SILHOUETTES') : 'EXPLORE OUR COMPLETE CATALOG OF CONTEMPORARY READY-TO-WEAR & FABRICS'); ?>

            </p>
        </div>
    </div>

    <div class="collections-layout">
        <aside class="collections-sidebar">
            <div class="sidebar-group">
                <h3 class="sidebar-title">CATEGORIES</h3>
                <ul class="sidebar-list">
                    <li class="<?php echo e(empty($categorySlug) ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('/collections')); ?>">All Pieces (<?php echo e($categories->sum('products_count')); ?>)</a>
                    </li>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="<?php echo e($categorySlug === $cat->slug ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('/collections?category=' . urlencode($cat->slug))); ?>">
                                <?php echo e($cat->name); ?> (<?php echo e($cat->products_count); ?>)
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </aside>

        <main class="collections-main">
            <div class="results-info">
                SHOWING <?php echo e($products->count()); ?> <?php echo e(\Illuminate\Support\Str::plural('PIECE', $products->count())); ?>

            </div>

            <?php if($products->isNotEmpty()): ?>
                <div class="collections-grid-v2">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('partials.product-card', [
                            'product' => $product,
                            'delayClass' => 'delay-' . min(($loop->index % 3 + 1) * 100, 300),
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="empty-results">
                    <h3>No items found in this collection</h3>
                    <p style="color: #737373; margin: 15px 0 25px;">Check back soon or explore our other collections.</p>
                    <a href="<?php echo e(url('/collections')); ?>" class="clear-filters">VIEW ALL PIECES</a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/collections.blade.php ENDPATH**/ ?>