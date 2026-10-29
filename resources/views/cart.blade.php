@extends('layouts.app')

@section('title', 'Shopping Cart | Leede Fusion')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    @include('partials.navbar')

    <div class="cart-page-container" x-data="{
        form: { name: '', phone: '', email: '', city: '', address: '', notes: '' },
        errors: {},
        message: '',
        placing: false,
        placed: null,
        num(p) {
            return Number(String(p).replace(/[^0-9.]/g, '')) || 0;
        },
        money(n) {
            return 'Rs. ' + Number(n || 0).toLocaleString('en-PK');
        },
        get subtotal() {
            return $store.cart.items.reduce((sum, item) => sum + this.num(item.price) * item.quantity, 0);
        },
        async checkout() {
            this.placing = true;
            this.errors = {};
            this.message = '';
            try {
                const res = await fetch(@js(route('checkout')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        ...this.form,
                        items: $store.cart.items.map((i) => ({ id: i.id, quantity: i.quantity, size: i.size || null })),
                    }),
                });
                const json = await res.json().catch(() => ({}));
                if (res.status === 422) {
                    this.errors = json.errors || {};
                    this.message = Object.keys(this.errors).some((k) => k.startsWith('items'))
                        ? 'Some items in your cart are no longer available. Please remove them and try again.'
                        : 'Please check the highlighted fields.';
                    return;
                }
                if (res.status === 429) {
                    this.message = 'Too many attempts. Please wait a minute and try again.';
                    return;
                }
                if (!res.ok) throw new Error();
                this.placed = json;
                $store.cart.clear();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (e) {
                this.message = 'Something went wrong. Please try again or order on WhatsApp.';
            } finally {
                this.placing = false;
            }
        },
    }">
        <div class="cart-content-wrapper">
            {{-- Order placed --}}
            <template x-if="placed">
                <div class="track-card track-card-wide cart-success">
                    <span class="track-celebrate-icon" aria-hidden="true">✦</span>
                    <h1 class="studio-title">Thank you! <em>Order placed.</em></h1>
                    <p class="track-lead">Your order reference is</p>
                    <p class="track-code" x-text="placed.reference"></p>
                    <p class="track-lead">Tap below to send your order to us on WhatsApp so we can confirm delivery. You can track it any time.</p>
                    <div class="track-actions" style="justify-content: center;">
                        <a :href="placed.whatsapp_url" target="_blank" rel="noopener noreferrer" class="pdp-btn-whatsapp">Confirm on WhatsApp</a>
                        <a :href="placed.track_url" class="studio-btn-ghost">Track my order</a>
                    </div>
                </div>
            </template>

            <template x-if="!placed">
                <div>
                    <h1 class="page-title">SHOPPING CART</h1>

                    <template x-if="$store.cart.items.length > 0">
                        <div class="cart-layout">
                            <div class="cart-items-list">
                                <template x-for="item in $store.cart.items" :key="item.key">
                                    <div class="cart-item">
                                        <div class="cart-item-img">
                                            <img :src="item.image || '/hero-fashion.webp'" :alt="item.name">
                                        </div>
                                        <div class="cart-item-details">
                                            <div class="item-header">
                                                <h3 class="item-name">
                                                    <a :href="item.slug ? '/collections/' + item.slug : '#'" x-text="item.name"></a>
                                                </h3>
                                                <button type="button" class="remove-item" @click="$store.cart.remove(item.key)">Remove</button>
                                            </div>
                                            <div class="item-meta">
                                                <span x-show="item.size">Size: <strong x-text="item.size"></strong> · </span>
                                                <span>Price: <span x-text="money(num(item.price))"></span></span>
                                            </div>
                                            <div class="item-footer">
                                                <div class="quantity-controls">
                                                    <button type="button" aria-label="Decrease quantity" @click="$store.cart.updateQuantity(item.key, -1)">−</button>
                                                    <span x-text="item.quantity"></span>
                                                    <button type="button" aria-label="Increase quantity" @click="$store.cart.updateQuantity(item.key, 1)">+</button>
                                                </div>
                                                <div style="font-weight: 700;" x-text="money(num(item.price) * item.quantity)"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <a href="{{ route('design') }}" class="pdp-customize" style="margin-top: 8px;">
                                    <span class="pdp-customize-icon" aria-hidden="true">✦</span>
                                    <span><strong>Want something one of a kind?</strong> Design your own outfit in the Design Studio.</span>
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>

                            <form class="cart-summary-card" @submit.prevent="checkout()" novalidate>
                                <h3 class="summary-title">ORDER SUMMARY</h3>
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span x-text="money(subtotal)"></span>
                                </div>
                                <div class="summary-row">
                                    <span>Delivery</span>
                                    <span>Confirmed on WhatsApp</span>
                                </div>
                                <div class="summary-total">
                                    <span>Total</span>
                                    <span x-text="money(subtotal)"></span>
                                </div>

                                <h4 class="checkout-heading">DELIVERY DETAILS</h4>
                                <div class="checkout-fields">
                                    @foreach ([
                                        ['name', 'Full name *', 'text', 'name'],
                                        ['phone', 'WhatsApp number *', 'tel', 'tel'],
                                        ['city', 'City *', 'text', 'address-level2'],
                                        ['email', 'Email (optional)', 'email', 'email'],
                                    ] as [$field, $label, $type, $auto])
                                        <label class="studio-field" :class="{ 'has-error': errors.{{ $field }} }">
                                            <span>{{ $label }}</span>
                                            <input type="{{ $type }}" x-model.trim="form.{{ $field }}" autocomplete="{{ $auto }}" maxlength="255">
                                            <small class="field-error" x-show="errors.{{ $field }}" x-text="errors.{{ $field }}?.[0]"></small>
                                        </label>
                                    @endforeach
                                    <label class="studio-field" :class="{ 'has-error': errors.address }">
                                        <span>Full address *</span>
                                        <textarea rows="2" x-model.trim="form.address" autocomplete="street-address" maxlength="500"></textarea>
                                        <small class="field-error" x-show="errors.address" x-text="errors.address?.[0]"></small>
                                    </label>
                                    <label class="studio-field">
                                        <span>Notes (optional)</span>
                                        <textarea rows="2" x-model.trim="form.notes" maxlength="1000" placeholder="Gift wrap, preferred delivery time…"></textarea>
                                    </label>
                                </div>

                                <p class="checkout-error" x-show="message" x-text="message" role="alert"></p>

                                <button type="submit" class="checkout-btn" :disabled="placing" x-text="placing ? 'PLACING ORDER…' : 'PLACE ORDER · CASH ON DELIVERY'"></button>
                                <p class="summary-note">
                                    Free studio pickup in Karachi. Nationwide delivery through trusted couriers. We confirm every order on WhatsApp.
                                </p>
                            </form>
                        </div>
                    </template>

                    <template x-if="$store.cart.items.length === 0">
                        <div class="empty-results">
                            <h3 style="font-family: var(--font-heading); font-size: 28px; margin-bottom: 12px;">YOUR CART IS EMPTY</h3>
                            <p style="color: #737373; margin-bottom: 30px;">Looks like you haven't added anything to your cart yet.</p>
                            <a href="{{ route('collections') }}" class="clear-filters">EXPLORE COLLECTIONS</a>
                            <a href="{{ route('design') }}" class="clear-filters" style="margin-left: 8px;">DESIGN YOUR OWN</a>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

    @include('partials.footer')
@endsection
