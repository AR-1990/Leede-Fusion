<?php $__env->startSection('title', 'The House — Our Genesis, Philosophy & Craft | Leede Fusion'); ?>
<?php $__env->startSection('description', 'An intimate look into LEEDE FUSION — our genesis, design philosophy, vision, and commitment to modern women\'s custom design and bespoke tailoring in Karachi.'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="the-house-page">
        <!-- Hero Header -->
        <header class="house-hero">
            <div class="house-hero-inner reveal reveal-fade-up">
                <span class="house-page-badge">DEDICATED PAGE</span>
                <h1 class="house-main-title">THE HOUSE</h1>
                <p class="house-main-subtitle">
                    An intimate look into LEEDE FUSION — our genesis, design philosophy, vision, and commitment to modern women's custom design.
                </p>
            </div>
        </header>

        <!-- Full-Width Atelier Visual Banner -->
        <div class="house-visual-banner reveal reveal-fade-up delay-100" x-data="imageLoader()">
            <div class="skeleton skeleton-img" x-show="!loaded" x-transition.opacity.duration.300ms style="position: absolute; inset: 0;"></div>
            <img
                src="/the-house-hero.jpg"
                alt="LEEDE FUSION Atelier & Design House"
                class="house-banner-img"
                :class="{ 'img-loaded': loaded }"
                @load="onLoad()"
                loading="lazy"
            >
            <div class="house-banner-caption">
                <span>LEEDE FUSION ATELIER • KARACHI, PAKISTAN</span>
            </div>
        </div>

        <!-- Main Editorial Narrative Container -->
        <main class="house-narrative-container">
            
            <!-- 01 / BRAND IDENTITY -->
            <section class="house-chapter reveal reveal-fade-up">
                <div class="chapter-grid">
                    <div class="chapter-text">
                        <span class="chapter-number">01 / BRAND IDENTITY</span>
                        <h2 class="chapter-title">WHO WE ARE</h2>
                        <div class="chapter-prose">
                            <p class="lead-text">
                                <strong>LEEDE FUSION</strong> is a modern women's fashion and custom design house. We are dedicated exclusively to women's fashion, offering a personalized alternative to standard off-the-rack fashion stores.
                            </p>
                            <p>
                                Our design house is centered around individual expression, modest luxury, and refined craftsmanship. We believe that clothing should be a reflection of the woman wearing it — thoughtfully cut to her body form, stitched according to her specific preferences, and constructed from premium textiles.
                            </p>
                        </div>
                    </div>
                    <div class="chapter-media reveal reveal-slide-right delay-150" x-data="imageLoader()">
                        <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
                        <img
                            src="/house-who-we-are.jpg"
                            alt="Who We Are - Leede Fusion House Identity"
                            class="chapter-img"
                            :class="{ 'img-loaded': loaded }"
                            @load="onLoad()"
                            loading="lazy"
                        >
                    </div>
                </div>
            </section>

            <hr class="house-divider">

            <!-- 02 / GENESIS -->
            <section class="house-chapter reveal reveal-fade-up">
                <div class="chapter-grid reverse">
                    <div class="chapter-text">
                        <span class="chapter-number">02 / GENESIS</span>
                        <h2 class="chapter-title">OUR STORY</h2>
                        <div class="chapter-prose">
                            <p class="lead-text">
                                Born from a deep reverence for heritage craftsmanship and contemporary women's silhouettes, LEEDE FUSION was established in Karachi as a bespoke design studio with a singular mission: to make custom, master-tailored fashion effortless, luxurious, and accessible.
                            </p>
                            <p>
                                While fast fashion prioritizes mass production over individual fit, our atelier honors the art of pattern drafting, delicate hand-embroidery, and master tailoring. Every piece is cut with precision, customized to the client's measurements, and crafted from the finest pure silks, luxury lawns, cambrics, and jacquards.
                            </p>
                        </div>
                    </div>
                    <div class="chapter-media reveal reveal-slide-left delay-150" x-data="imageLoader()">
                        <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
                        <img
                            src="/the-house-craft.jpg"
                            alt="Our Story - Master Tailoring Craft at Leede Fusion"
                            class="chapter-img"
                            :class="{ 'img-loaded': loaded }"
                            @load="onLoad()"
                            loading="lazy"
                        >
                    </div>
                </div>
            </section>

            <hr class="house-divider">

            <!-- 03 / AMBITION -->
            <section class="house-chapter reveal reveal-fade-up">
                <div class="chapter-single-col">
                    <span class="chapter-number">03 / AMBITION</span>
                    <h2 class="chapter-title">OUR VISION</h2>
                    <p class="chapter-vision-text">
                        Our vision is to redefine how women experience fashion by restoring the personal relationship between the client, the designer, and the craft. We aim to elevate modest design and bespoke tailoring into an accessible, seamless luxury experience where every woman can bring her design ideas to life with flawless fit and execution.
                    </p>
                </div>

                <!-- Dual Cards: Design Philosophy & Our Approach -->
                <div class="philosophy-cards-grid reveal reveal-fade-up delay-150">
                    <div class="philosophy-card">
                        <span class="philosophy-card-badge">THE PRINCIPLE</span>
                        <h3 class="philosophy-card-title">DESIGN PHILOSOPHY</h3>
                        <p class="philosophy-card-body">
                            We believe in thoughtful silhouettes, fluid proportions, and timeless elegance over fleeting trends. Every seam, cut, and fabric choice is guided by purpose, modesty, and modern sophistication.
                        </p>
                    </div>

                    <div class="philosophy-card">
                        <span class="philosophy-card-badge">THE METHOD</span>
                        <h3 class="philosophy-card-title">OUR APPROACH</h3>
                        <p class="philosophy-card-body">
                            We listen to your idea, examine reference materials or inspirations, select the appropriate weight and drape of fabric, draft a pattern around your body form, and execute the creation with master crafting.
                        </p>
                    </div>
                </div>
            </section>

            <!-- The House 4 Pillars of Craft -->
            <section class="house-pillars-section reveal reveal-fade-up delay-200">
                <div class="pillars-header">
                    <span class="pillars-tag">ATELIER STANDARD</span>
                    <h3>FOUR PILLARS OF OUR CRAFT</h3>
                </div>

                <div class="pillars-grid">
                    <div class="pillar-item">
                        <div class="pillar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.3 8.7 8.7 21.3c-1 1-2.5 1-3.4 0l-2.6-2.6c-1-1-1-2.5 0-3.4L15.3 2.7c1-1 2.5-1 3.4 0l2.6 2.6c1 1 1 2.5 0 3.4Z"></path>
                                <path d="m14.5 3.5 2 2"></path>
                                <path d="m11.5 6.5 2 2"></path>
                                <path d="m8.5 9.5 2 2"></path>
                            </svg>
                        </div>
                        <h4>01. Exact Made-to-Measure</h4>
                        <p>Custom shoulders, chest, waist, hips, and length proportions tailored to your exact height.</p>
                    </div>

                    <div class="pillar-item">
                        <div class="pillar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </div>
                        <h4>02. Reference to Reality</h4>
                        <p>Reproducing Pinterest, Instagram, or sketchbook inspirations with faithful silhouettes.</p>
                    </div>

                    <div class="pillar-item">
                        <div class="pillar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="6" cy="6" r="3"></circle>
                                <circle cx="6" cy="18" r="3"></circle>
                                <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                                <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                                <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h4>03. Master Fabric Sourcing</h4>
                        <p>Pure Swiss lawns, silks, raw silks, organza, and jacquards sourced directly from master weavers.</p>
                    </div>

                    <div class="pillar-item">
                        <div class="pillar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h4>04. Couture Finishing</h4>
                        <p>Hand-inspected seams, fine overlocking, delicate piping, and steam-pressed packaging.</p>
                    </div>
                </div>
            </section>

            <!-- Bottom Master CTA -->
            <div class="house-cta-wrapper reveal reveal-fade-up">
                <a href="<?php echo e(route('contact')); ?>?inquiry=Custom+Stitching" class="house-primary-cta-btn">
                    START YOUR DESIGN WITH THE HOUSE
                </a>
            </div>

        </main>
    </div>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/story.blade.php ENDPATH**/ ?>