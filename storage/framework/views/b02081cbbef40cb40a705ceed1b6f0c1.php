<?php $__env->startSection('title', 'The Atelier — Fabric Library, Studio Tools & Custom Design | Leede Fusion'); ?>
<?php $__env->startSection('description', 'Where Ideas Become Designs. Explore our studio creative pipeline, curated fabric library, and interactive custom idea visualizer.'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="the-house-page">
        
        <header class="house-hero">
            <div class="house-hero-inner reveal reveal-fade-up">
                <span class="house-page-badge">DEDICATED PAGE</span>
                <h1 class="house-main-title">THE ATELIER</h1>
                <p class="house-main-subtitle">
                    Where Ideas Become Designs. Explore our studio's creative pipeline, fabric library, and moodboard visualizer.
                </p>
            </div>
        </header>

        
        <div class="house-visual-banner reveal reveal-fade-up delay-100" x-data="imageLoader()">
            <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
            <img
                src="/atelier-hero.jpg"
                alt="LEEDE FUSION Atelier Studio"
                class="house-banner-img"
                :class="{ 'img-loaded': loaded }"
                @load="onLoad()"
                loading="lazy"
            >
            <div class="house-banner-caption">
                <span>LEEDE FUSION ATELIER • KARACHI, PAKISTAN</span>
            </div>
        </div>

        
        <main class="house-narrative-container">

            
            <section class="house-chapter reveal reveal-fade-up" id="fabrics">
                <div class="chapter-single-col">
                    <span class="chapter-number">THE SOURCE</span>
                    <h2 class="chapter-title">FABRIC &amp; MATERIAL LIBRARY</h2>
                    <p class="chapter-vision-text">
                        Every custom piece begins with selecting the ideal fabric weight, texture, and drape.
                    </p>
                </div>

                
                <div class="atelier-fabrics-3col-grid reveal reveal-fade-up delay-100">
                    
                    <div class="atelier-fabric-item-card">
                        <div class="fabric-item-media" x-data="imageLoader()">
                            <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
                            <img
                                src="/fabric-silk-crepe.jpg"
                                alt="Japanese Silk Crepe"
                                class="fabric-item-img"
                                :class="{ 'img-loaded': loaded }"
                                @load="onLoad()"
                                loading="lazy"
                            >
                        </div>
                        <div class="fabric-item-content">
                            <span class="fabric-item-badge">PREMIUM CREPE</span>
                            <h3 class="fabric-item-title">Japanese Silk Crepe</h3>
                            <p class="fabric-item-desc">
                                Ultra-smooth, matte finish with a medium drape weight. Ideal for structured yet fluid abayas and daily tailored sets.
                            </p>
                        </div>
                    </div>

                    
                    <div class="atelier-fabric-item-card">
                        <div class="fabric-item-media" x-data="imageLoader()">
                            <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
                            <img
                                src="/fabric-organza.jpg"
                                alt="French Silk Organza"
                                class="fabric-item-img"
                                :class="{ 'img-loaded': loaded }"
                                @load="onLoad()"
                                loading="lazy"
                            >
                        </div>
                        <div class="fabric-item-content">
                            <span class="fabric-item-badge">LAYERED SHEER</span>
                            <h3 class="fabric-item-title">French Silk Organza</h3>
                            <p class="fabric-item-desc">
                                Lightweight translucent mesh providing airy volume and subtle sheen for statement sleeves and outer drapes.
                            </p>
                        </div>
                    </div>

                    
                    <div class="atelier-fabric-item-card">
                        <div class="fabric-item-media" x-data="imageLoader()">
                            <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
                            <img
                                src="/fabric-satin.jpg"
                                alt="Duchess Satin & Chiffon"
                                class="fabric-item-img"
                                :class="{ 'img-loaded': loaded }"
                                @load="onLoad()"
                                loading="lazy"
                            >
                        </div>
                        <div class="fabric-item-content">
                            <span class="fabric-item-badge">LUXURY LUSTER</span>
                            <h3 class="fabric-item-title">Duchess Satin &amp; Chiffon</h3>
                            <p class="fabric-item-desc">
                                Richly woven silk blend featuring subtle surface sheen, designed for evening abayas and formal tailored pieces.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="house-divider">

            
            <section class="house-chapter reveal reveal-fade-up" id="visualizer" x-data="atelierVisualizerTool()">
                <div class="chapter-single-col">
                    <span class="chapter-number">ATELIER STUDIO TOOL</span>
                    <h2 class="chapter-title">CUSTOM IDEA VISUALIZER</h2>
                    <p class="chapter-vision-text">
                        Select design elements below to build a preliminary concept outline before consulting with our team.
                    </p>
                </div>

                
                <div class="visualizer-selectors-grid reveal reveal-fade-up delay-100">
                    
                    <div class="visualizer-select-group">
                        <label class="visualizer-select-label">1. SELECT GARMENT TYPE</label>
                        <div class="select-wrapper">
                            <select x-model="garmentType" class="visualizer-native-select">
                                <option value="Traditional Closed Abaya">Traditional Closed Abaya</option>
                                <option value="Modern Open Kimono Abaya">Modern Open Kimono Abaya</option>
                                <option value="Tailored Silk Pret Kurta & Trouser">Tailored Silk Pret Kurta & Trouser</option>
                                <option value="Royal Kaftan with Flowing Drapes">Royal Kaftan with Flowing Drapes</option>
                                <option value="Luxury 3-Piece Festive Set">Luxury 3-Piece Festive Set</option>
                                <option value="Custom Sketch / Client Provided Design">Custom Sketch / Client Provided Design</option>
                            </select>
                            <svg class="select-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    
                    <div class="visualizer-select-group">
                        <label class="visualizer-select-label">2. PREFERRED FABRIC</label>
                        <div class="select-wrapper">
                            <select x-model="fabric" class="visualizer-native-select">
                                <option value="Japanese Matte Silk Crepe">Japanese Matte Silk Crepe</option>
                                <option value="French Silk Organza">French Silk Organza</option>
                                <option value="Duchess Satin & Chiffon">Duchess Satin & Chiffon</option>
                                <option value="Pure Raw Dupioni Silk">Pure Raw Dupioni Silk</option>
                                <option value="Premium Egyptian Cotton & Lawn">Premium Egyptian Cotton & Lawn</option>
                                <option value="Royal Micro Velvet">Royal Micro Velvet</option>
                            </select>
                            <svg class="select-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    
                    <div class="visualizer-select-group">
                        <label class="visualizer-select-label">3. SLEEVE DETAIL</label>
                        <div class="select-wrapper">
                            <select x-model="sleeve" class="visualizer-native-select">
                                <option value="Straight Tailored Sleeve">Straight Tailored Sleeve</option>
                                <option value="French Lace Bell Sleeve">French Lace Bell Sleeve</option>
                                <option value="Bishop Cuffed Sleeve with Pearl Buttons">Bishop Cuffed Sleeve with Pearl Buttons</option>
                                <option value="Scallop Hand-Embroidered Edge">Scallop Hand-Embroidered Edge</option>
                                <option value="Flowing Cape & Slit Silhouette">Flowing Cape & Slit Silhouette</option>
                            </select>
                            <svg class="select-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>

                
                <div class="atelier-concept-box-clean reveal reveal-fade-up delay-150">
                    <span class="concept-box-clean-tag">YOUR CONCEPT SUMMARY</span>
                    <h3 class="concept-box-clean-summary" x-text="conceptSummary">
                        Traditional Closed Abaya in Japanese Matte Silk Crepe with Straight Tailored Sleeve
                    </h3>
                    <button type="button" class="concept-box-clean-btn" @click="proceedWithConcept()">
                        PROCEED WITH THIS CONCEPT
                    </button>
                </div>
            </section>

            <hr class="house-divider">

            
            <section class="house-chapter reveal reveal-fade-up">
                <div class="philosophy-cards-grid">
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

            <hr class="house-divider">

            
            <section class="house-pillars-section reveal reveal-fade-up">
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

            
            <div class="house-cta-wrapper reveal reveal-fade-up">
                <a href="<?php echo e(route('contact')); ?>?inquiry=Custom+Stitching" class="house-primary-cta-btn">
                    START YOUR DESIGN WITH THE ATELIER
                </a>
            </div>

        </main>
    </div>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function atelierVisualizerTool() {
    return {
        garmentType: 'Traditional Closed Abaya',
        fabric: 'Japanese Matte Silk Crepe',
        sleeve: 'Straight Tailored Sleeve',
        get conceptSummary() {
            return `${this.garmentType} in ${this.fabric} with ${this.sleeve}`;
        },
        proceedWithConcept() {
            const encoded = encodeURIComponent(`[Atelier Visualizer Concept]\n${this.conceptSummary}`);
            window.location.href = `<?php echo e(route('contact')); ?>?inquiry=Custom+Stitching&concept=${encoded}`;
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/atelier.blade.php ENDPATH**/ ?>