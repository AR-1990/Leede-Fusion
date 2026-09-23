import Alpine from 'alpinejs';

const API_BASE = '/api';

document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: [],
        get count() {
            return this.items.reduce((sum, item) => sum + item.quantity, 0);
        },
        init() {
            try {
                const saved = localStorage.getItem('wahla_cart');
                if (saved) this.items = JSON.parse(saved);
            } catch {
                this.items = [];
            }
        },
        persist() {
            localStorage.setItem('wahla_cart', JSON.stringify(this.items));
        },
        add(product, quantity = 1) {
            const existing = this.items.find((item) => item.name === product.name);
            if (existing) {
                existing.quantity += quantity;
            } else {
                this.items.push({
                    id: product.id || Math.random(),
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity,
                });
            }
            this.persist();
            window.dispatchEvent(new CustomEvent('cart-updated'));
        },
        remove(name) {
            this.items = this.items.filter((item) => item.name !== name);
            this.persist();
        },
        updateQuantity(name, delta) {
            this.items = this.items.map((item) => {
                if (item.name !== name) return item;
                return { ...item, quantity: Math.max(1, item.quantity + delta) };
            });
            this.persist();
        },
        clear() {
            this.items = [];
            this.persist();
        },
    });

    Alpine.store('auth', {
        user: null,
        token: null,
        get isAuthenticated() {
            return !!this.user;
        },
        get isAdmin() {
            return !!this.user?.is_admin;
        },
        init() {
            try {
                const savedUser = localStorage.getItem('wahla_user');
                const savedToken = localStorage.getItem('wahla_token');
                if (savedUser && savedToken) {
                    this.user = JSON.parse(savedUser);
                    this.token = savedToken;
                }
            } catch {
                this.user = null;
                this.token = null;
            }
        },
        persist() {
            if (this.user && this.token) {
                localStorage.setItem('wahla_user', JSON.stringify(this.user));
                localStorage.setItem('wahla_token', this.token);
            } else {
                localStorage.removeItem('wahla_user');
                localStorage.removeItem('wahla_token');
            }
        },
        async login(email, password) {
            const response = await fetch(`${API_BASE}/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }
            this.user = data.user;
            this.token = data.access_token;
            this.persist();
            return this.user;
        },
        async register(name, email, password) {
            const response = await fetch(`${API_BASE}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ name, email, password }),
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Registration failed');
            }
            this.user = data.user;
            this.token = data.access_token;
            this.persist();
            return this.user;
        },
        async logout() {
            if (this.token) {
                try {
                    await fetch(`${API_BASE}/logout`, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            Authorization: `Bearer ${this.token}`,
                        },
                    });
                } catch {
                    /* ignore offline logout */
                }
            }
            this.user = null;
            this.token = null;
            this.persist();
        },
    });
});

window.navbar = function navbar() {
    return {
        menuOpen: false,
        collectionsOpen: false,
        mobileCollectionsOpen: false,
        profileOpen: false,
        cartAnimating: false,
        init() {
            window.addEventListener('cart-updated', () => {
                this.cartAnimating = true;
                setTimeout(() => {
                    this.cartAnimating = false;
                }, 300);
            });
        },
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
            if (!this.menuOpen) {
                this.mobileCollectionsOpen = false;
            }
            this.collectionsOpen = false;
            this.profileOpen = false;
        },
        closeMenus() {
            this.menuOpen = false;
            this.collectionsOpen = false;
            this.mobileCollectionsOpen = false;
            this.profileOpen = false;
        },
        toggleCollections() {
            this.collectionsOpen = !this.collectionsOpen;
            this.profileOpen = false;
        },
        toggleMobileCollections() {
            this.mobileCollectionsOpen = !this.mobileCollectionsOpen;
        },
        onProfileClick() {
            this.collectionsOpen = false;
            if (Alpine.store('auth').isAuthenticated) {
                this.profileOpen = !this.profileOpen;
            } else {
                window.location.href = '/login';
            }
        },
    };
};

window.preloader = function preloader() {
    return {
        count: 0,
        visible: true,
        leaving: false,
        init() {
            const timer = setInterval(() => {
                if (this.count >= 100) {
                    clearInterval(timer);
                    setTimeout(() => {
                        this.leaving = true;
                        window.dispatchEvent(new CustomEvent('preloader-finished'));
                        setTimeout(() => {
                            this.visible = false;
                            if (typeof window.initScrollReveal === 'function') {
                                window.initScrollReveal();
                            }
                        }, 800);
                    }, 400);
                    return;
                }
                this.count += 2;
            }, 20);
        },
    };
};

window.heroSlider = function heroSlider(slides) {
    const duration = 5000;
    return {
        slides,
        current: 0,
        startedAt: Date.now(),
        timer: null,
        get titleHtml() {
            const title = this.slides[this.current]?.title || '';
            return title.replace(', ', ',<br />');
        },
        init() {
            this.slides.forEach((slide) => {
                const img = new Image();
                img.src = slide.image;
            });
            this.start();
        },
        start() {
            clearInterval(this.timer);
            this.startedAt = Date.now();
            this.timer = setInterval(() => {
                this.current = (this.current + 1) % this.slides.length;
                this.startedAt = Date.now();
            }, duration);
        },
        goTo(index) {
            this.current = index;
            this.start();
        },
        barStyle(index) {
            if (index < this.current) return { width: '100%' };
            if (index > this.current) return { width: '0%' };
            return {
                width: '100%',
                transition: `width ${duration}ms linear`,
            };
        },
    };
};

window.featuredScroll = function featuredScroll() {
    return {
        scroll(direction) {
            const el = this.$refs.scroller;
            if (!el) return;
            const amount = el.clientWidth;
            el.scrollTo({
                left: direction === 'left' ? el.scrollLeft - amount : el.scrollLeft + amount,
                behavior: 'smooth',
            });
        },
    };
};

window.quickView = function quickView() {
    return {
        open: false,
        product: null,
        quantity: 1,
        imageIndex: 0,
        added: false,
        showAuthWarning: false,
        modalImageLoaded: false,
        get gallery() {
            if (!this.product) return [];
            if (Array.isArray(this.product.images) && this.product.images.length) {
                return this.product.images;
            }
            return [this.product.image || '/hero-fashion.webp'];
        },
        get currentImage() {
            return this.gallery[this.imageIndex] || '/hero-fashion.webp';
        },
        openWith(detail) {
            this.product = detail;
            this.quantity = 1;
            this.imageIndex = 0;
            this.modalImageLoaded = false;
            this.added = false;
            this.showAuthWarning = false;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        addToCart() {
            if (!Alpine.store('auth').isAuthenticated) {
                this.showAuthWarning = true;
                setTimeout(() => {
                    this.showAuthWarning = false;
                }, 3000);
                return;
            }

            Alpine.store('cart').add(
                {
                    id: this.product.id,
                    name: this.product.name,
                    price: this.product.price,
                    image: this.currentImage,
                },
                this.quantity,
            );

            this.added = true;
            setTimeout(() => {
                this.added = false;
                this.close();
            }, 1500);
        },
    };
};

window.imageLoader = function imageLoader() {
    return {
        loaded: false,
        init() {
            this.$nextTick(() => {
                const img = this.$el.querySelector('img');
                if (img && img.complete) {
                    this.loaded = true;
                }
            });
        },
        onLoad() {
            this.loaded = true;
        },
    };
};

window.initScrollReveal = function initScrollReveal() {
    const elements = document.querySelectorAll('.reveal');
    if (!elements.length) return;

    if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        elements.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                obs.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px',
    });

    elements.forEach((el) => {
        if (!el.classList.contains('is-revealed')) {
            observer.observe(el);
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    window.initScrollReveal();
});

window.addEventListener('preloader-finished', () => {
    // Immediately reveal hero elements once preloader starts leaving
    document.querySelectorAll('.hero-container .reveal').forEach((el) => {
        el.classList.add('is-revealed');
    });
    // Trigger check for other above-the-fold reveals
    setTimeout(() => {
        window.initScrollReveal();
    }, 100);
});

window.Alpine = Alpine;
Alpine.start();
