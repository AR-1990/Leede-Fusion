<div class="preloader" x-data="preloader()" x-show="visible" x-cloak
     x-transition:leave="preloader-leave"
     :style="leaving ? 'transform: translateY(-100%)' : ''">
    <div class="preloader-content">
        <h1 class="preloader-logo">
            <img src="<?php echo e(asset('Logo.png')); ?>" alt="Leede Fusion logo">
            <span><span class="brand-word-primary">LEEDE</span> <span class="brand-word-secondary">FUSION</span></span>
        </h1>
        <div class="preloader-counter">
            <span class="counter-number" x-text="count"></span>
            <span class="counter-symbol">%</span>
        </div>
    </div>
</div>
<?php /**PATH /Users/mac/Documents/GitHub/Leede-Fusion/resources/views/partials/preloader.blade.php ENDPATH**/ ?>