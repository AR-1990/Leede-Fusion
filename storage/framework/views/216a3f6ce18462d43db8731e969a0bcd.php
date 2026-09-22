<?php
    use App\Support\Catalog;
    $image = Catalog::primaryImage($product);
    $categoryLabel = Catalog::categoryLabel($product);
    $gallery = Catalog::gallery($product);
?>

<div class="product-card reveal reveal-fade-up <?php echo e($delayClass ?? ''); ?>">
    <div class="product-img-wrapper" x-data="imageLoader()">
        <div class="skeleton skeleton-img" x-show="!loaded" x-transition.opacity.duration.400ms></div>
        <?php if($product->tag): ?>
            <span class="product-tag"><?php echo e($product->tag); ?></span>
        <?php endif; ?>
        <img
            src="<?php echo e($image); ?>"
            alt="<?php echo e($product->name); ?>"
            class="product-img"
            :class="{ 'img-loaded': loaded }"
            @load="onLoad()"
            loading="lazy"
        >
        <button
            type="button"
            class="product-enquire-btn"
            @click="$dispatch('open-quick-view', {
                id: <?php echo e($product->id); ?>,
                name: <?php echo \Illuminate\Support\Js::from($product->name)->toHtml() ?>,
                price: <?php echo \Illuminate\Support\Js::from($product->price)->toHtml() ?>,
                description: <?php echo \Illuminate\Support\Js::from($product->description ?? 'Premium quality from Leede Fusion.')->toHtml() ?>,
                image: <?php echo \Illuminate\Support\Js::from($image)->toHtml() ?>,
                images: <?php echo \Illuminate\Support\Js::from($gallery)->toHtml() ?>,
                category: <?php echo \Illuminate\Support\Js::from($categoryLabel)->toHtml() ?>,
                tag: <?php echo \Illuminate\Support\Js::from($product->tag)->toHtml() ?>,
            })"
        >
            QUICK VIEW
        </button>
    </div>
    <div class="product-info">
        <h3 class="product-name"><?php echo e($product->name); ?></h3>
        <p class="product-category-label"><?php echo e($categoryLabel); ?></p>
        <div class="product-price">
            <span class="current-price">Rs. <?php echo e($product->price); ?></span>
            <?php if($product->old_price): ?>
                <span class="old-price">Rs. <?php echo e($product->old_price); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/partials/product-card.blade.php ENDPATH**/ ?>