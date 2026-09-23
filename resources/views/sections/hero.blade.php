<section class="hero-container" x-data="heroSlider(@js($heroSlides))" @mouseenter="pause()" @mouseleave="resume()">
    <div class="hero" :class="slides[current]?.theme === 'light' ? 'hero-theme-light' : 'hero-theme-dark'">
        <!-- Background Images Stack -->
        <div class="hero-img-stack">
            <div class="skeleton skeleton-dark skeleton-img"></div>
            <template x-for="(slide, index) in slides" :key="slide.id">
                <div
                    class="hero-slide-bg-wrap"
                    :style="{
                        position: 'absolute',
                        inset: 0,
                        zIndex: index === current ? 1 : 0,
                        opacity: index === current ? 1 : 0,
                        transform: index === current ? 'scale(1)' : 'scale(1.06)',
                        transition: 'opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 1.2s cubic-bezier(0.4, 0, 0.2, 1)',
                    }"
                >
                    <img
                        :src="slide.image"
                        :alt="slide.title"
                        class="hero-img"
                    >
                </div>
            </template>
        </div>

        <!-- Dynamic Overlay (Light high-key for slide 1 as per design, gradient dark for others) -->
        <div class="hero-overlay" :class="slides[current]?.theme === 'light' ? 'overlay-high-key' : 'overlay-editorial-dark'"></div>

        <!-- Navigation Arrows (Desktop & Tablet) -->
        <button type="button" class="hero-nav-arrow hero-nav-prev" @click="prev()" aria-label="Previous slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>
        <button type="button" class="hero-nav-arrow hero-nav-next" @click="next()" aria-label="Next slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </button>

        <!-- Center Content Matching Reference Image -->
        <div class="hero-center-content">
            <!-- Brand Monogram Emblem -->
            <div class="hero-emblem reveal reveal-fade-up delay-100">
                <svg width="34" height="34" viewBox="0 0 40 40" fill="currentColor" aria-hidden="true">
                    <path d="M20 2L36 18L28 26L20 18L12 26L4 18L20 2Z" opacity="0.9"/>
                    <path d="M20 38L12 30L20 22L28 30L20 38Z" fill="currentColor"/>
                </svg>
            </div>

            <!-- Overline Badge -->
            <div class="hero-badge reveal reveal-fade-up delay-150" x-text="slides[current]?.badge || 'MODERN WOMEN\'S DESIGN HOUSE'"></div>

            <!-- Main Headline with luxury serif -->
            <h1 class="hero-editorial-title reveal reveal-fade-up delay-200" x-text="slides[current]?.title">
                YOUR IDEA. OUR CRAFT.
            </h1>

            <!-- Subtitle Description -->
            <p class="hero-editorial-subtitle reveal reveal-fade-up delay-300" x-text="slides[current]?.description">
                Women's designs, custom stitching, bespoke Abayas and thoughtful fitting — created around you.
            </p>

            <!-- Dual Action Buttons -->
            <div class="hero-btn-group reveal reveal-fade-up delay-400">
                <!-- Primary Action: Start Your Design -> Jump to Contact Form -->
                <button
                    type="button"
                    class="hero-btn-primary"
                    @click="handlePrimaryCta(slides[current])"
                >
                    <span x-text="slides[current]?.primary_btn_text || 'START YOUR DESIGN'">START YOUR DESIGN</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>

                <!-- Secondary Action: Explore Collection -> Go to /collections -->
                <a
                    :href="slides[current]?.secondary_btn_url || '{{ url('/collections') }}'"
                    class="hero-btn-secondary"
                    @click="handleSecondaryCta($event, slides[current])"
                >
                    <span x-text="slides[current]?.secondary_btn_text || 'EXPLORE COLLECTION'">EXPLORE COLLECTION</span>
                </a>
            </div>
        </div>

        <!-- Modern Progress & Slide Switcher at Bottom -->
        <div class="hero-bottom-indicators reveal reveal-fade-up delay-400">
            <template x-for="(slide, index) in slides" :key="'indicator-' + slide.id">
                <button
                    type="button"
                    class="hero-indicator-item"
                    :class="{ 'is-active': index === current }"
                    @click="goTo(index)"
                    :aria-label="'Go to slide ' + (index + 1)"
                >
                    <div class="indicator-bar-track">
                        <div class="indicator-bar-fill" :style="barStyle(index)"></div>
                    </div>
                    <div class="indicator-meta">
                        <span class="indicator-num" x-text="'0' + (index + 1)"></span>
                        <span class="indicator-title" x-text="slide.title"></span>
                    </div>
                </button>
            </template>
        </div>
    </div>
</section>
