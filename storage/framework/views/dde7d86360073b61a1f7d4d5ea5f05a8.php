<section class="hero-container" x-data="heroSlider(<?php echo \Illuminate\Support\Js::from($heroSlides)->toHtml() ?>)">
    <div class="hero">
        <div class="hero-img-stack">
            <div class="skeleton skeleton-dark skeleton-img"></div>
            <template x-for="(slide, index) in slides" :key="slide.id">
                <img
                    :src="slide.image"
                    :alt="slide.title"
                    class="hero-img"
                    :style="{
                        position: 'absolute',
                        inset: 0,
                        zIndex: index === current ? 1 : 0,
                        opacity: index === current ? 1 : 0,
                        transform: index === current ? 'scale(1)' : 'scale(1.05)',
                        transition: 'opacity 1s ease-in-out, transform 1s ease-in-out',
                    }"
                >
            </template>
        </div>

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div :key="current" x-transition>
                <h1 class="hero-title reveal reveal-fade-up delay-100" x-html="titleHtml"></h1>
                <p class="hero-description reveal reveal-fade-up delay-200" x-text="slides[current]?.description"></p>
            </div>

            <button type="button" class="shop-now-btn reveal reveal-fade-up delay-300" @click="document.getElementById('collections')?.scrollIntoView({ behavior: 'smooth' })">
                Explore Collection <span>→</span>
            </button>
        </div>

        <div class="hero-progress reveal reveal-fade-up delay-400">
            <template x-for="(slide, index) in slides" :key="'progress-' + slide.id">
                <div
                    class="progress-item"
                    :class="{ active: index === current }"
                    @click="goTo(index)"
                >
                    <div class="progress-bar-bg">
                        <div
                            class="progress-bar-fill"
                            :style="barStyle(index)"
                        ></div>
                    </div>
                    <div class="progress-number" x-text="'0' + slide.id"></div>
                    <div class="progress-label" x-text="slide.title"></div>
                </div>
            </template>
        </div>
    </div>
</section>
<?php /**PATH /Users/mac/Documents/GitHub/Leede-Fusion/resources/views/sections/hero.blade.php ENDPATH**/ ?>