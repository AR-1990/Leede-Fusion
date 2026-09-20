<div class="modal-overlay"
     x-data="quickView()"
     x-show="open"
     x-cloak
     @open-quick-view.window="openWith($event.detail)"
     @close-quick-view.window="close()"
     @click.self="close()"
     x-transition.opacity>
    <div class="quick-view-modal" @click.stop x-show="open" x-transition>
        <button type="button" class="modal-close" @click="close()">×</button>

        <div class="modal-grid" x-show="product">
            <div class="modal-image-side" style="position: relative;">
                <div class="skeleton skeleton-img" x-show="!modalImageLoaded" x-transition.opacity.duration.300ms style="border-radius: var(--border-radius);"></div>
                <img :src="currentImage" :alt="product?.name" @load="modalImageLoaded = true" :class="{ 'img-loaded': modalImageLoaded }">
                <template x-if="gallery.length > 1">
                    <div style="display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap;">
                        <template x-for="(src, i) in gallery" :key="src + i">
                            <button
                                type="button"
                                @click="imageIndex = i; modalImageLoaded = false"
                                :style="{
                                    padding: 0,
                                    border: i === imageIndex ? '2px solid #111' : '1px solid #ddd',
                                    borderRadius: '4px',
                                    overflow: 'hidden',
                                    width: '56px',
                                    height: '70px',
                                    cursor: 'pointer',
                                    background: 'none',
                                }"
                            >
                                <img :src="src" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <div class="modal-content-side">
                <div class="modal-header">
                    <span class="modal-category" x-text="product?.category"></span>
                    <h2 class="modal-title" x-text="product?.name"></h2>
                    <div class="modal-price">Rs. <span x-text="product?.price"></span></div>
                </div>

                <div class="modal-body">
                    <p class="modal-description" x-text="product?.description"></p>

                    <div class="modal-features">
                        <div class="feature-item">✓ Premium quality</div>
                        <div class="feature-item">✓ Curated catalog</div>
                        <div class="feature-item">✓ Studio pickup Karachi</div>
                    </div>

                    <div class="quantity-selector-wrapper">
                        <span class="quantity-label">SELECT QUANTITY</span>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" @click="quantity = Math.max(1, quantity - 1)">−</button>
                            <span class="qty-value" x-text="quantity"></span>
                            <button type="button" class="qty-btn" @click="quantity++">+</button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="auth-warning-msg" x-show="showAuthWarning" x-cloak x-transition>
                        Please login first to place order.
                        <button type="button" @click="window.location.href = '/login'">Login Now</button>
                    </div>

                    <button
                        type="button"
                        class="modal-whatsapp-btn"
                        :class="{ success: added, warning: showAuthWarning }"
                        @click="addToCart()"
                        :disabled="added"
                        x-text="added ? 'ADDED TO CART ✓' : 'ADD TO CART'"
                    ></button>
                    <p class="modal-footer-note"
                       x-text="added ? 'Item added to your cart!' : 'Select quantity and add to your cart for checkout.'"></p>
                </div>
            </div>
        </div>
    </div>
</div>
