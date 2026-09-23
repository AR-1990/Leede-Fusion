<?php $__env->startSection('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'Collections | Leede Fusion'); ?>

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

    <div class="collections-layout collections-layout-products-only">
        <main class="collections-main">
            <section class="collections-filters">
                <div class="collections-filters-head">
                    <h2 class="collections-filters-title">Filter by Category</h2>
                    <p class="collections-filters-copy">Choose a category to see only its products.</p>
                </div>

                <div class="collections-filters-grid">
                    <a href="<?php echo e(url('/collections')); ?>" class="collection-filter-card <?php echo e(empty($categorySlug) ? 'is-active' : ''); ?>">
                        <span class="collection-filter-name">All Pieces</span>
                        <span class="collection-filter-meta"><?php echo e($categories->sum('products_count')); ?> pieces</span>
                    </a>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('/collections?category=' . urlencode($cat->slug))); ?>" class="collection-filter-card <?php echo e($categorySlug === $cat->slug ? 'is-active' : ''); ?>">
                            <span class="collection-filter-name"><?php echo e($cat->name); ?></span>
                            <span class="collection-filter-meta"><?php echo e($cat->products_count); ?> <?php echo e(\Illuminate\Support\Str::plural('piece', $cat->products_count)); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>

            <div class="collections-toolbar">
                <div class="results-info">
                    SHOWING <?php echo e($products->firstItem() ?? 0); ?>-<?php echo e($products->lastItem() ?? 0); ?> OF <?php echo e($products->total()); ?> <?php echo e(\Illuminate\Support\Str::plural('PIECE', $products->total())); ?>

                    <?php if($selectedCategory): ?>
                        IN <?php echo e(strtoupper($selectedCategory->name)); ?>

                    <?php endif; ?>
                </div>

                <?php if($selectedCategory): ?>
                    <a href="<?php echo e(url('/collections')); ?>" class="collections-reset-link">View All Pieces</a>
                <?php endif; ?>
            </div>

            <?php if($products->isNotEmpty()): ?>
                <div class="collections-grid-v2">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('partials.product-card', [
                            'product' => $product,
                            'delayClass' => 'delay-' . min(($loop->index % 4 + 1) * 100, 400),
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($products->hasPages()): ?>
                    <div class="collections-pagination">
                        <?php $__currentLoopData = $products->onEachSide(1)->linkCollection(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($link['url']): ?>
                                <a href="<?php echo e($link['url']); ?>" class="collections-pagination-link <?php echo e($link['active'] ? 'is-active' : ''); ?>">
                                    <?php echo $link['label']; ?>

                                </a>
                            <?php else: ?>
                                <span class="collections-pagination-link is-disabled"><?php echo $link['label']; ?></span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
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