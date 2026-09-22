<?php $__env->startSection('title', 'Our Story | Leede'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="story-hero">
        <div class="story-hero-content reveal reveal-fade-up">
            <span class="story-tag">CRAFT & HERITAGE</span>
            <h1 class="story-title">OUR STORY</h1>
            <p class="story-subtitle">Crafting timeless elegance and modern streetwear from Karachi to the world.</p>
        </div>
    </section>

    <div class="section-container" style="max-width: 1400px; margin: 0 auto; padding: 0 40px;">
        <div class="story-grid">
            <div class="story-text reveal reveal-slide-left">
                <h2>THE GENESIS</h2>
                <p>
                    Founded with a passion for exceptional textiles, Leede represents the intersection
                    of heritage craftsmanship and contemporary streetwear aesthetics.
                </p>
                <p>
                    Every fabric is meticulously chosen, from breathable summer lawns to heavy 450GSM
                    winter fleece, ensuring comfort, elegance, and durability in every piece.
                </p>
                <ul class="story-list">
                    <li>100% Premium Curated Fabrics</li>
                    <li>Designed & Crafted in Karachi</li>
                    <li>Ethical Sourcing & Modern Cuts</li>
                </ul>
            </div>
            <div class="story-image reveal reveal-slide-right delay-200">
                <img src="/1.jpg" alt="Our Craft">
            </div>
        </div>

        <div class="story-grid reverse">
            <div class="story-text reveal reveal-slide-right">
                <h2>THE VISION</h2>
                <p>
                    We believe style should be effortless yet distinguished. Whether it's our signature
                    unstitched fabrics for traditional celebrations or our relaxed oversized silhouettes,
                    Leede brings a new standard of accessible luxury.
                </p>
                <a href="<?php echo e(url('/collections')); ?>" class="shop-now-btn" style="display: inline-block; margin-top: 20px;">
                    Explore Collections <span>→</span>
                </a>
            </div>
            <div class="story-image reveal reveal-slide-left delay-200">
                <img src="/2.jpg" alt="Our Vision">
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/story.blade.php ENDPATH**/ ?>