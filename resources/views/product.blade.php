@extends('layouts.app')

@php
    use App\Support\Catalog;
    $gallery = Catalog::gallery($product);
    $categoryLabel = Catalog::categoryLabel($product);
    $plainDescription = trim(strip_tags((string) $product->description));
@endphp

@section('title', $product->name.' | '.($categoryLabel ?: 'Collection').' | Leede Fusion Karachi')
@section('description', \Illuminate\Support\Str::limit($plainDescription ?: $product->name.' from Leede Fusion, Karachi. Ready to wear or customized to your measurements, delivered nationwide.', 155))
@section('og_type', 'product')
@section('og_image', $gallery[0])

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => array_map(fn ($img) => url($img), $gallery),
            'description' => $plainDescription ?: $product->name,
            'sku' => 'LF-'.$product->id,
            'brand' => ['@type' => 'Brand', 'name' => 'Leede Fusion'],
            'category' => $categoryLabel,
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product),
                'priceCurrency' => 'PKR',
                'price' => $product->priceValue(),
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    @include('partials.navbar')

    <main class="pdp" x-data="{
        images: @js($gallery),
        active: 0,
        size: '',
        quantity: 1,
        added: false,
        warn: false,
        add() {
            if (!this.size) { this.warn = true; setTimeout(() => this.warn = false, 2500); return; }
            $store.cart.add({ id: {{ $product->id }}, slug: @js($product->slug), name: @js($product->name), price: @js($product->price), image: this.images[0], size: this.size }, this.quantity);
            this.added = true;
            setTimeout(() => this.added = false, 2200);
        },
    }">
        <nav class="journal-breadcrumb pdp-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('collections') }}">Collections</a>
            @if ($product->category)
                / <a href="{{ route('collections', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            @endif
        </nav>

        <div class="pdp-grid">
            {{-- Gallery --}}
            <section class="pdp-gallery reveal reveal-fade-up" aria-label="Product images">
                <div class="pdp-main-image">
                    @if ($product->tag)
                        <span class="product-tag">{{ $product->tag }}</span>
                    @endif
                    <img :src="images[active]" src="{{ $gallery[0] }}" alt="{{ $product->name }}">
                    <template x-if="images.length > 1">
                        <div class="pdp-arrows">
                            <button type="button" aria-label="Previous image" @click="active = (active - 1 + images.length) % images.length">&larr;</button>
                            <button type="button" aria-label="Next image" @click="active = (active + 1) % images.length">&rarr;</button>
                        </div>
                    </template>
                </div>
                @if (count($gallery) > 1)
                    <div class="pdp-thumbs">
                        @foreach ($gallery as $i => $img)
                            <button type="button" class="pdp-thumb" :class="{ 'is-active': active === {{ $i }} }" @click="active = {{ $i }}" aria-label="Show image {{ $i + 1 }}">
                                <img src="{{ $img }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Details --}}
            <section class="pdp-info reveal reveal-fade-up delay-100">
                @if ($categoryLabel)
                    <span class="pdp-kicker">{{ $categoryLabel }}</span>
                @endif
                <h1 class="pdp-title">{{ $product->name }}</h1>
                <div class="pdp-price">
                    <span class="current-price">Rs. {{ number_format($product->priceValue()) }}</span>
                    @if ($product->old_price)
                        <span class="old-price">Rs. {{ $product->old_price }}</span>
                    @endif
                </div>

                @if ($plainDescription)
                    <div class="pdp-description">{!! nl2br(e($plainDescription)) !!}</div>
                @endif

                <div class="pdp-option">
                    <div class="pdp-option-head">
                        <span class="quantity-label">SELECT SIZE</span>
                        <a href="{{ route('the-fit') }}" class="size-guide-link">Size guide</a>
                    </div>
                    <div class="size-chips">
                        @foreach (\App\Models\CustomOrder::SIZES as $s)
                            <button type="button" class="size-chip" :class="{ 'is-active': size === @js($s) }" @click="size = @js($s)">{{ $s }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="pdp-option">
                    <span class="quantity-label">QUANTITY</span>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn" @click="quantity = Math.max(1, quantity - 1)" aria-label="Decrease quantity">−</button>
                        <span class="qty-value" x-text="quantity">1</span>
                        <button type="button" class="qty-btn" @click="quantity = Math.min(20, quantity + 1)" aria-label="Increase quantity">+</button>
                    </div>
                </div>

                <div class="auth-warning-msg" x-show="warn" x-cloak x-transition>Please select your size first.</div>

                <div class="pdp-actions">
                    <button type="button" class="pdp-btn-primary" @click="add()" :class="{ 'is-added': added }" x-text="added ? 'ADDED TO CART ✓' : 'ADD TO CART'">ADD TO CART</button>
                    <a href="{{ config('content.project.whatsapp_link') }}?text={{ rawurlencode('Hi Leede Fusion! I am interested in "'.$product->name.'" ('.route('product.show', $product).')') }}"
                       target="_blank" rel="noopener noreferrer" class="pdp-btn-whatsapp">ASK ON WHATSAPP</a>
                </div>
                <a x-show="added" x-cloak href="{{ route('cart') }}" class="pdp-cart-link">View cart &amp; checkout &rarr;</a>

                {{-- The bespoke hook: every ready article can be customized. --}}
                <a href="{{ route('design', ['product' => $product->slug]) }}" class="pdp-customize">
                    <span class="pdp-customize-icon" aria-hidden="true">✦</span>
                    <span>
                        <strong>Make it yours</strong>
                        Change the fabric, neckline, sleeves or embroidery, or get it stitched to your exact measurements.
                    </span>
                    <span aria-hidden="true">&rarr;</span>
                </a>

                <div class="pdp-details">
                    <details open>
                        <summary>Delivery & Pickup</summary>
                        <p>Free studio pickup in Karachi. Nationwide delivery through trusted couriers, cash on delivery available.</p>
                    </details>
                    <details>
                        <summary>Fit & Alterations</summary>
                        <p>Between sizes? Choose the closer one and tell us on WhatsApp. Small alterations after delivery are on us. See the <a href="{{ route('the-fit') }}">size guide</a>.</p>
                    </details>
                    <details>
                        <summary>Care</summary>
                        <p>Gentle hand wash or dry clean for embellished pieces. Iron on reverse at medium heat.</p>
                    </details>
                </div>
            </section>
        </div>

        @if ($related->isNotEmpty())
            <section class="pdp-related">
                <div class="blog-header-wrapper reveal reveal-fade-up">
                    <span class="blog-kicker">YOU MAY ALSO LOVE</span>
                    <h2 class="section-title">COMPLETE THE LOOK</h2>
                </div>
                <div class="collections-grid-v2">
                    @foreach ($related as $item)
                        @include('partials.product-card', ['product' => $item, 'delayClass' => 'delay-'.(($loop->index % 4 + 1) * 100)])
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @include('partials.footer')
@endsection
