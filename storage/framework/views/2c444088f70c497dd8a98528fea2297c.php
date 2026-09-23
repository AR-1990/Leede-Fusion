<div class="preloader luxury-preloader" x-data="preloader()" x-show="visible" x-cloak
     x-transition:leave="preloader-leave"
     :class="{ 'is-leaving': leaving }">
    <div class="preloader-bg-ambient"></div>
    <div class="preloader-grid-lines"></div>

    <div class="preloader-inner-container">
        
        <div class="preloader-emblem-wrap">
            <svg class="preloader-orbit-svg" viewBox="0 0 160 160" width="160" height="160">
                <!-- Outer gold ring -->
                <circle cx="80" cy="80" r="74" class="orbit-circle-outer" />
                <!-- Rotating dashed couture stitch ring -->
                <circle cx="80" cy="80" r="66" class="orbit-stitch-ring" />
                <!-- Orbiting gold gem node -->
                <circle cx="80" cy="14" r="4" class="orbit-gold-node" />
            </svg>

            <div class="preloader-logo-frame">
                <img src="<?php echo e(asset('Logo.png')); ?>" alt="Leede Fusion Logo" class="preloader-logo-img">
            </div>
        </div>

        
        <div class="preloader-branding-text">
            <h2 class="preloader-brand-name">
                <span class="gold-gradient-text">LEEDE</span> <span>FUSION</span>
            </h2>
            <p class="preloader-brand-subtitle">ATELIER &amp; BESPOKE COUTURE &bull; KARACHI</p>
        </div>

        
        <div class="preloader-status-container">
            <div class="preloader-progress-track">
                <div class="preloader-progress-bar" :style="'width: ' + count + '%'">
                    <span class="progress-laser-flare"></span>
                </div>
            </div>

            <div class="preloader-status-meta">
                <span class="preloader-status-phase" x-text="statusText">INITIALIZING ATELIER...</span>
                <span class="preloader-status-percent">
                    <span class="percent-val" x-text="Math.floor(count)">0</span>%
                </span>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/partials/preloader.blade.php ENDPATH**/ ?>