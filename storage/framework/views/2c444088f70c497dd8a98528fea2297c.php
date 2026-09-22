<div class="preloader" x-data="preloader()" x-show="visible" x-cloak
     x-transition:leave="preloader-leave"
     :style="leaving ? 'transform: translateY(-100%)' : ''">
    <div class="preloader-content">
        <h1 class="preloader-logo">
            <?php $__currentLoopData = str_split('LEEDE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $char): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span><?php echo $char === ' ' ? '&nbsp;' : e($char); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </h1>
        <div class="preloader-counter">
            <span class="counter-number" x-text="count"></span>
            <span class="counter-symbol">%</span>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/partials/preloader.blade.php ENDPATH**/ ?>