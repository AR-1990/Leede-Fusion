<div class="top-bar">
    <div class="social-icons">
        <a href="<?php echo e(config('content.project.whatsapp_link')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon-link" aria-label="WhatsApp" title="WhatsApp">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-icon-link" aria-label="Instagram" title="Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <circle cx="12" cy="12" r="4" />
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
            </svg>
        </a>
        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-icon-link" aria-label="Facebook" title="Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
        </a>
    </div>
    <div class="top-nav-links">
        <span>Karachi</span>
        <span>9:00 AM – 9:00 PM</span>
    </div>
</div>

<header class="navbar-sticky-wrapper" x-data="navbar()">

    <nav class="navbar">
        <a href="<?php echo e(route('home')); ?>" class="navbar-logo">Leede</a>

        <div class="navbar-links desktop-only">
            <a href="<?php echo e(route('home')); ?>">Home</a>
            <a href="<?php echo e(url('/collections')); ?>">Collections</a>
            <a href="#all-collections">Men</a>
            <a href="#all-collections">Women</a>
            <a href="#categories">Kids</a>
            <a href="#categories">Accessories</a>
            <a href="<?php echo e(url('/story')); ?>">Our Story</a>
            <a href="#contact">Contact</a>
        </div>

        <div class="navbar-actions">
            <a href="<?php echo e(url('/cart')); ?>" class="navbar-cart-link">
                <div class="cart-icon-wrapper" :class="{ 'cart-bounce': cartAnimating }">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span class="cart-badge" x-show="$store.cart.count > 0" x-text="$store.cart.count" x-cloak></span>
                </div>
            </a>

            <div class="navbar-profile-wrapper">
                <button type="button" class="navbar-profile-btn" @click="onProfileClick()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="profile-dot" x-show="$store.auth.isAuthenticated" x-cloak></span>
                </button>

                <div class="profile-dropdown"
                     x-show="profileOpen && $store.auth.isAuthenticated"
                     x-cloak
                     @click.outside="profileOpen = false"
                     x-transition>
                    <div class="dropdown-header">
                        <p class="user-name" x-text="$store.auth.user?.name"></p>
                        <p class="user-email" x-text="$store.auth.user?.email"></p>
                    </div>
                    <div class="dropdown-body">
                        <template x-if="$store.auth.isAdmin">
                            <a href="<?php echo e(url('/admin')); ?>" style="font-weight: 700; color: #111; border-left: 3px solid #111; padding-left: 17px;" @click="profileOpen = false">Admin Dashboard &rarr;</a>
                        </template>
                        <a href="<?php echo e(url('/profile')); ?>" @click="profileOpen = false">My Profile</a>
                        <a href="<?php echo e(url('/orders')); ?>" @click="profileOpen = false">My Orders</a>
                        <button type="button" @click="$store.auth.logout(); profileOpen = false">Logout</button>
                    </div>
                </div>
            </div>

            <button type="button" class="menu-toggle mobile-only" @click="menuOpen = !menuOpen" aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path x-show="!menuOpen" d="M3 12h18M3 6h18M3 18h18"></path>
                    <path x-show="menuOpen" d="M18 6L6 18M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div class="mobile-menu" x-show="menuOpen" x-cloak x-transition:enter.opacity.duration.300ms>
        <div class="mobile-menu-links">
            <a href="<?php echo e(route('home')); ?>" @click="menuOpen = false">Home</a>
            <a href="<?php echo e(url('/collections')); ?>" @click="menuOpen = false">Collections</a>
            <a href="#all-collections" @click="menuOpen = false">Men</a>
            <a href="#all-collections" @click="menuOpen = false">Women</a>
            <a href="#categories" @click="menuOpen = false">Kids</a>
            <a href="#categories" @click="menuOpen = false">Accessories</a>
            <a href="<?php echo e(url('/story')); ?>" @click="menuOpen = false">Our Story</a>
            <a href="#contact" @click="menuOpen = false">Contact</a>

            <template x-if="$store.auth.isAuthenticated">
                <div>
                    <template x-if="$store.auth.isAdmin">
                        <a href="<?php echo e(url('/admin')); ?>" style="font-weight: 700; color: #111;" @click="menuOpen = false">Admin Dashboard</a>
                    </template>
                    <a href="<?php echo e(url('/profile')); ?>" @click="menuOpen = false">My profile</a>
                    <a href="<?php echo e(url('/orders')); ?>" @click="menuOpen = false">My orders</a>
                    <button type="button" class="mobile-auth-btn" @click="$store.auth.logout(); menuOpen = false">Logout</button>
                </div>
            </template>
            <template x-if="!$store.auth.isAuthenticated">
                <a href="<?php echo e(url('/login')); ?>" @click="menuOpen = false" class="mobile-auth-btn">Login / Register</a>
            </template>

            <a href="<?php echo e(url('/cart')); ?>" @click="menuOpen = false" class="mobile-cart-link">
                Cart (<span x-text="$store.cart.count"></span>)
            </a>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/partials/navbar.blade.php ENDPATH**/ ?>