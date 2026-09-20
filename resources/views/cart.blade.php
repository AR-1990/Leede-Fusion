@extends('layouts.app')

@section('title', 'Shopping Cart | Leede')

@section('content')
    @include('partials.navbar')

    <div class="cart-page-container" x-data="{
        formatPrice(p) {
            return Number(String(p).replace(/[^0-9.]/g, '')).toLocaleString();
        },
        get subtotal() {
            return $store.cart.items.reduce((sum, item) => {
                const num = Number(String(item.price).replace(/[^0-9.]/g, '')) || 0;
                return sum + (num * item.quantity);
            }, 0);
        }
    }">
        <div class="cart-content-wrapper">
            <h1 class="page-title reveal reveal-fade-up">SHOPPING CART</h1>

            <template x-if="$store.cart.items.length > 0">
                <div class="cart-layout">
                    <div class="cart-items-list">
                        <template x-for="item in $store.cart.items" :key="item.name">
                            <div class="cart-item reveal reveal-fade-up">
                                <div class="cart-item-img">
                                    <img :src="item.image || '/hero-fashion.webp'" :alt="item.name">
                                </div>
                                <div class="cart-item-details">
                                    <div class="item-header">
                                        <h3 class="item-name" x-text="item.name"></h3>
                                        <button type="button" class="remove-item" @click="$store.cart.remove(item.name)">Remove</button>
                                    </div>
                                    <div class="item-meta">
                                        <span>Price: Rs. <span x-text="formatPrice(item.price)"></span></span>
                                    </div>
                                    <div class="item-footer">
                                        <div class="quantity-controls">
                                            <button type="button" @click="$store.cart.updateQuantity(item.name, -1)">−</button>
                                            <span x-text="item.quantity"></span>
                                            <button type="button" @click="$store.cart.updateQuantity(item.name, 1)">+</button>
                                        </div>
                                        <div style="font-weight: 700;">
                                            Rs. <span x-text="formatPrice((Number(String(item.price).replace(/[^0-9.]/g, '')) || 0) * item.quantity)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="cart-summary-card reveal reveal-fade-up delay-150">
                        <h3 class="summary-title">ORDER SUMMARY</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>Rs. <span x-text="formatPrice(subtotal)"></span></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rs. <span x-text="formatPrice(subtotal)"></span></span>
                        </div>
                        <p class="summary-note">
                            Free studio pickup available in Karachi. Orders delivered via Bykea & TCS nationwide.
                        </p>
                        <a href="https://wa.me/{{ config('content.project.whatsapp_international') }}?text=Hello%2C%20I%20would%20like%20to%20place%20an%20order%20for%20items%20in%20my%20cart." target="_blank" rel="noopener noreferrer" class="checkout-btn" style="display: block; text-align: center; text-decoration: none;">
                            CHECKOUT VIA WHATSAPP
                        </a>
                    </div>
                </div>
            </template>

            <template x-if="$store.cart.items.length === 0">
                <div class="empty-results reveal reveal-scale-up">
                    <h3 style="font-family: var(--font-heading); font-size: 28px; margin-bottom: 12px;">YOUR CART IS EMPTY</h3>
                    <p style="color: #737373; margin-bottom: 30px;">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ url('/collections') }}" class="clear-filters">EXPLORE COLLECTIONS</a>
                </div>
            </template>
        </div>
    </div>

    @include('partials.footer')
@endsection
