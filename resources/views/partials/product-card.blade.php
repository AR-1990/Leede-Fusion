@php
    use App\Support\Catalog;
    $image = Catalog::primaryImage($product);
    $categoryLabel = Catalog::categoryLabel($product);
    $gallery = Catalog::gallery($product);
@endphp

<div class="product-card reveal reveal-fade-up {{ $delayClass ?? '' }}">
    <div class="product-img-wrapper" x-data="imageLoader()">
        <div class="skeleton skeleton-img" x-show="!loaded" x-transition.opacity.duration.400ms></div>
        @if($product->tag)
            <span class="product-tag">{{ $product->tag }}</span>
        @endif
        <img
            src="{{ $image }}"
            alt="{{ $product->name }}"
            class="product-img"
            :class="{ 'img-loaded': loaded }"
            @load="onLoad()"
            loading="lazy"
        >
        <button
            type="button"
            class="product-enquire-btn"
            @click="$dispatch('open-quick-view', {
                id: {{ $product->id }},
                name: @js($product->name),
                price: @js($product->price),
                description: @js($product->description ?? 'Premium quality from Leede.'),
                image: @js($image),
                images: @js($gallery),
                category: @js($categoryLabel),
                tag: @js($product->tag),
            })"
        >
            QUICK VIEW
        </button>
    </div>
    <div class="product-info">
        <h3 class="product-name">{{ $product->name }}</h3>
        <p class="product-category-label">{{ $categoryLabel }}</p>
        <div class="product-price">
            <span class="current-price">Rs. {{ $product->price }}</span>
            @if($product->old_price)
                <span class="old-price">Rs. {{ $product->old_price }}</span>
            @endif
        </div>
    </div>
</div>
