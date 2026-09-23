<section class="house-story-section">
    <div class="house-story-container">
        <div class="house-story-grid">
            <!-- Left Column: Editorial Brand Story -->
            <div class="house-story-content reveal reveal-slide-left">
                <span class="house-story-overline">THE HOUSE</span>
                <h2 class="house-story-title">WHO WE ARE</h2>
                <p class="house-story-text">
                    LEEDEFUSION is a modern women's fashion and custom design house. We focus exclusively on women's fashion, providing custom stitching, tailored abayas, and personalized garment creation.
                </p>
                <div class="house-story-cta">
                    <a href="<?php echo e(route('story')); ?>" class="house-story-btn">
                        READ OUR STORY
                    </a>
                </div>
            </div>

            <!-- Right Column: High-Fashion Flatlay Image -->
            <div class="house-story-media reveal reveal-slide-right delay-200" x-data="imageLoader()">
                <div class="skeleton skeleton-img" x-show="!loaded" x-transition.opacity.duration.300ms style="position: absolute; inset: 0;"></div>
                <img
                    src="/house-who-we-are.jpg"
                    alt="LEEDE FUSION - Modern Women's Fashion & Custom Design House"
                    class="house-story-img"
                    :class="{ 'img-loaded': loaded }"
                    @load="onLoad()"
                    loading="lazy"
                >
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/house-story.blade.php ENDPATH**/ ?>