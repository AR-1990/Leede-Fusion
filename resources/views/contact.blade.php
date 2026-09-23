@extends('layouts.app')

@section('title', 'Bespoke Atelier & Contact Us | Leede Fusion')
@section('description', 'Bring your own design, get bespoke clothes stitched, explore ready collections, or book bulk B2B manufacturing with Leede Fusion Karachi.')

@section('content')
    @include('partials.navbar')

    <div class="contact-page-wrapper">
        <!-- Luxury Atelier Contact Hero -->
        <section class="contact-hero-luxury">
            <div class="contact-hero-container reveal reveal-fade-up">
                <span class="contact-hero-tag">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    LEEDE FUSION ATELIER &amp; DESIGN HOUSE
                </span>

                <h1 class="contact-hero-title">
                    YOUR DESIGN. OUR <em>MASTER CRAFT.</em>
                </h1>

                <p class="contact-hero-subtitle">
                    Have a dress idea, sketch, or picture from Pinterest or Instagram? We manufacture custom clothes tailored to your exact measurements, alongside our signature in-house pret and B2B bulk production.
                </p>

                <!-- 4 Quick Channels Grid -->
                <div class="quick-channels-grid reveal reveal-fade-up delay-100">
                    <a href="#atelier-form" @click="$dispatch('select-inquiry-type', { type: 'Custom Stitching / Sizing' })" class="quick-channel-card">
                        <div class="quick-channel-header">
                            <span class="quick-channel-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="6" cy="6" r="3"></circle>
                                    <circle cx="6" cy="18" r="3"></circle>
                                    <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                                    <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                                    <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                                </svg>
                            </span>
                            <span style="font-size: 11px; font-weight: 700; color: #d4af37; letter-spacing: 0.1em;">BESPOKE</span>
                        </div>
                        <div>
                            <h4>Custom Stitching</h4>
                            <p>Bring your own photo or sketch for custom tailoring.</p>
                        </div>
                    </a>

                    <a href="https://wa.me/{{ config('content.project.whatsapp_international', '923066978156') }}?text={{ urlencode('Hi Leede Fusion! I have a design photo and want to get clothes stitched. Can you please guide me?') }}" target="_blank" rel="noopener noreferrer" class="quick-channel-card">
                        <div class="quick-channel-header">
                            <span class="quick-channel-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 700; color: #22c55e;">
                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span>
                                ONLINE NOW
                            </span>
                        </div>
                        <div>
                            <h4>WhatsApp Atelier</h4>
                            <p>Send design pictures directly for instant quote.</p>
                        </div>
                    </a>

                    <a href="#atelier-form" @click="$dispatch('select-inquiry-type', { type: 'Wholesale / Bulk Orders' })" class="quick-channel-card">
                        <div class="quick-channel-header">
                            <span class="quick-channel-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                                    <path d="M17 18h1"></path>
                                    <path d="M12 18h1"></path>
                                    <path d="M7 18h1"></path>
                                </svg>
                            </span>
                            <span style="font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.6); letter-spacing: 0.1em;">B2B</span>
                        </div>
                        <div>
                            <h4>Bulk Manufacturing</h4>
                            <p>Sampling &amp; production for clothing brands.</p>
                        </div>
                    </a>

                    <a href="#studio-location" class="quick-channel-card">
                        <div class="quick-channel-header">
                            <span class="quick-channel-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </span>
                            <span style="font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.6); letter-spacing: 0.1em;">KARACHI</span>
                        </div>
                        <div>
                            <h4>Visit Our Studio</h4>
                            <p>In-person fitting &amp; fabric consultation.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Bespoke Creation Process Roadmap -->
        <section class="bespoke-process-wrap">
            <div class="process-card-glass reveal reveal-fade-up delay-150">
                <div class="process-section-title">
                    <span>HOW IT WORKS</span>
                    <h3>THE BESPOKE CREATION PROCESS</h3>
                </div>

                <div class="process-steps-grid">
                    <div class="process-step-item">
                        <div class="step-number">01</div>
                        <div class="step-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </div>
                        <h4>Share Your Design</h4>
                        <p>Send us a photo, sketch, or reference from social media with your preferred cuts &amp; styling.</p>
                    </div>

                    <div class="process-step-item">
                        <div class="step-number">02</div>
                        <div class="step-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.3 8.7 8.7 21.3c-1 1-2.5 1-3.4 0l-2.6-2.6c-1-1-1-2.5 0-3.4L15.3 2.7c1-1 2.5-1 3.4 0l2.6 2.6c1 1 1 2.5 0 3.4Z"></path>
                                <path d="m14.5 3.5 2 2"></path>
                                <path d="m11.5 6.5 2 2"></path>
                                <path d="m8.5 9.5 2 2"></path>
                                <path d="m5.5 12.5 2 2"></path>
                            </svg>
                        </div>
                        <h4>Fabric &amp; Sizing</h4>
                        <p>Choose from our luxury fabrics or provide your own. Give standard sizes or custom body measurements.</p>
                    </div>

                    <div class="process-step-item">
                        <div class="step-number">03</div>
                        <div class="step-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="6" cy="6" r="3"></circle>
                                <circle cx="6" cy="18" r="3"></circle>
                                <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                                <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                                <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h4>Master Crafting</h4>
                        <p>Our experienced Karachi pattern masters cut, embroider, and tailor your outfit with immaculate attention to detail.</p>
                    </div>

                    <div class="process-step-item">
                        <div class="step-number">04</div>
                        <div class="step-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h4>Quality Delivery</h4>
                        <p>Each garment is steam pressed, hand-inspected, and safely delivered to your doorstep nationwide or internationally.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Interactive Atelier & Contact Studio Form -->
        <main id="atelier-form" style="max-width: 1400px; margin: 0 auto; padding: 0 30px;">
            <div x-data="{
                activeTab: 'Custom Stitching / Sizing',
                selectedCategory: 'Women\'s Suits & Pret',
                fabricOption: 'Source fabric from Leede Fusion',
                sizingOption: 'Custom Measurements',
                form: {
                    name: '',
                    email: '',
                    phone: '',
                    subject: 'Custom Stitching / Sizing',
                    message: ''
                },
                loading: false,
                success: false,
                successMessage: '',
                errorMessage: '',
                errors: {},

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const inquiryParam = urlParams.get('inquiry');
                    const conceptParam = urlParams.get('concept');
                    if (inquiryParam) {
                        if (inquiryParam.toLowerCase().includes('custom') || inquiryParam.toLowerCase().includes('stitch')) {
                            this.setTab('Custom Stitching / Sizing');
                        } else if (inquiryParam.toLowerCase().includes('bulk') || inquiryParam.toLowerCase().includes('wholesale')) {
                            this.setTab('Wholesale / Bulk Orders');
                        }
                    }
                    if (conceptParam) {
                        this.setTab('Custom Stitching / Sizing');
                        this.form.message = `[Atelier Visualizer Selection]\n${decodeURIComponent(conceptParam)}\n\nPlease provide quotation and turnaround time.`;
                    }

                    window.addEventListener('select-inquiry-type', (e) => {
                        if (e.detail && e.detail.type) {
                            this.setTab(e.detail.type);
                        }
                    });
                },

                setTab(tab) {
                    this.activeTab = tab;
                    this.form.subject = tab;
                },

                async submitForm() {
                    this.loading = true;
                    this.errorMessage = '';
                    this.errors = {};

                    let structuredMessage = this.form.message;
                    if (this.activeTab === 'Custom Stitching / Sizing') {
                        structuredMessage = `[Category: ${this.selectedCategory}]\n[Fabric: ${this.fabricOption}]\n[Sizing: ${this.sizingOption}]\n\nDetails:\n${this.form.message}`;
                    }

                    try {
                        const res = await fetch('/api/contact', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                            },
                            body: JSON.stringify({
                                name: this.form.name,
                                email: this.form.email,
                                phone: this.form.phone,
                                subject: this.activeTab,
                                message: structuredMessage
                            })
                        });

                        const data = await res.json();
                        if (res.ok && data.success) {
                            this.success = true;
                            this.successMessage = data.message || 'Thank you! Your custom design inquiry has been sent to our master craftsman. We will contact you shortly.';
                            this.form = {
                                name: '',
                                email: '',
                                phone: '',
                                subject: this.activeTab,
                                message: ''
                            };
                        } else {
                            if (data.errors) {
                                this.errors = data.errors;
                            } else {
                                this.errorMessage = data.message || 'Could not submit inquiry. Please review details.';
                            }
                        }
                    } catch (e) {
                        this.errorMessage = 'Network connection issue. Please check your internet connection.';
                    } finally {
                        this.loading = false;
                    }
                }
            }">
                <div class="contact-wrapper-grid">
                    <!-- Left Column: Interactive Form -->
                    <div class="contact-form-card reveal reveal-fade-up">
                        <!-- Inquiry Mode Tabs -->
                        <div class="inquiry-type-selector">
                            <span class="selector-label">1. CHOOSE INQUIRY TYPE:</span>
                            <div class="inquiry-chips-grid">
                                <button
                                    type="button"
                                    class="inquiry-chip-btn"
                                    :class="{ 'is-selected': activeTab === 'Custom Stitching / Sizing' }"
                                    @click="setTab('Custom Stitching / Sizing')"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="6" cy="6" r="3"></circle>
                                        <circle cx="6" cy="18" r="3"></circle>
                                        <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                                        <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                                        <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                                    </svg>
                                    <span>Custom Design &amp; Stitching</span>
                                </button>
                                <button
                                    type="button"
                                    class="inquiry-chip-btn"
                                    :class="{ 'is-selected': activeTab === 'Ready Drops & Collections' }"
                                    @click="setTab('Ready Drops & Collections')"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                                        <path d="M3 6h18"></path>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span>Ready Collections &amp; Lawn</span>
                                </button>
                                <button
                                    type="button"
                                    class="inquiry-chip-btn"
                                    :class="{ 'is-selected': activeTab === 'Wholesale / Bulk Orders' }"
                                    @click="setTab('Wholesale / Bulk Orders')"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                                        <path d="M17 18h1"></path>
                                        <path d="M12 18h1"></path>
                                        <path d="M7 18h1"></path>
                                    </svg>
                                    <span>B2B &amp; Bulk Manufacturing</span>
                                </button>
                                <button
                                    type="button"
                                    class="inquiry-chip-btn"
                                    :class="{ 'is-selected': activeTab === 'Studio Consultation' }"
                                    @click="setTab('Studio Consultation')"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Studio Appointment</span>
                                </button>
                            </div>
                        </div>

                        <!-- Custom Design Attire Selector (When Custom Design is active) -->
                        <div x-show="activeTab === 'Custom Stitching / Sizing'" class="category-chips-wrap" x-transition>
                            <span class="selector-label">2. SELECT ATTIRE TYPE:</span>
                            <div class="category-chips-grid">
                                <button
                                    type="button"
                                    class="category-chip-btn"
                                    :class="{ 'is-active': selectedCategory === 'Women\'s Suits & Pret' }"
                                    @click="selectedCategory = 'Women\'s Suits & Pret'"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 3a3 3 0 0 0-3 3c0 1.3.8 2.4 2 2.8V10L2 15h20L13 10V8.8A3 3 0 0 0 12 3z"></path>
                                        <path d="M7 15v5a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-5"></path>
                                    </svg>
                                    <span>Women's Pret &amp; Suits</span>
                                </button>
                                <button
                                    type="button"
                                    class="category-chip-btn"
                                    :class="{ 'is-active': selectedCategory === 'Bespoke Abayas & Modest' }"
                                    @click="selectedCategory = 'Bespoke Abayas & Modest'"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.5L5 22h14l-5-12.5c1.2-.7 2-2 2-3.5a4 4 0 0 0-4-4z"></path>
                                        <line x1="12" y1="10" x2="12" y2="22"></line>
                                    </svg>
                                    <span>Bespoke Abayas</span>
                                </button>
                                <button
                                    type="button"
                                    class="category-chip-btn"
                                    :class="{ 'is-active': selectedCategory === 'Men\'s Kurta & Shalwar' }"
                                    @click="selectedCategory = 'Men\'s Kurta & Shalwar'"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 3h12l3 5-4 2-1-2v14H8V8L7 10 4 8z"></path>
                                        <path d="M12 3v8"></path>
                                    </svg>
                                    <span>Men's Kurta &amp; Couture</span>
                                </button>
                                <button
                                    type="button"
                                    class="category-chip-btn"
                                    :class="{ 'is-active': selectedCategory === 'Bridal & Formal Wear' }"
                                    @click="selectedCategory = 'Bridal & Formal Wear'"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m2 9 3-6 7 4 7-4 3 6-5 11H7L2 9z"></path>
                                        <path d="m2 9 10 3 10-3"></path>
                                    </svg>
                                    <span>Formal &amp; Party Wear</span>
                                </button>
                                <button
                                    type="button"
                                    class="category-chip-btn"
                                    :class="{ 'is-active': selectedCategory === 'Unstitched Sizing / Other' }"
                                    @click="selectedCategory = 'Unstitched Sizing / Other'"
                                >
                                    <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21.3 8.7 8.7 21.3c-1 1-2.5 1-3.4 0l-2.6-2.6c-1-1-1-2.5 0-3.4L15.3 2.7c1-1 2.5-1 3.4 0l2.6 2.6c1 1 1 2.5 0 3.4Z"></path>
                                        <path d="m14.5 3.5 2 2"></path>
                                        <path d="m11.5 6.5 2 2"></path>
                                        <path d="m8.5 9.5 2 2"></path>
                                        <path d="m5.5 12.5 2 2"></path>
                                    </svg>
                                    <span>Custom Cutting / Other</span>
                                </button>
                            </div>
                        </div>

                        <!-- Success Banner -->
                        <template x-if="success">
                            <div class="contact-success-banner">
                                <div class="contact-success-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <h3 class="contact-success-title">INQUIRY RECEIVED</h3>
                                <p class="contact-success-desc" x-text="successMessage"></p>
                                <button type="button" @click="success = false" class="contact-submit-btn" style="display: inline-flex; width: auto; margin: 0 auto; padding: 12px 28px;">
                                    SUBMIT ANOTHER INQUIRY
                                </button>
                            </div>
                        </template>

                        <!-- Active Form Fields -->
                        <template x-if="!success">
                            <form @submit.prevent="submitForm()" class="contact-form-fields" novalidate>
                                <div x-show="errorMessage" style="padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: 13px;" x-text="errorMessage"></div>

                                <div class="contact-form-row">
                                    <div class="contact-field-group">
                                        <label class="contact-label" for="page-name">YOUR FULL NAME *</label>
                                        <input type="text" id="page-name" x-model="form.name" placeholder="e.g. Ayesha Khan" class="contact-input" :class="{ 'has-error': errors.name }" required>
                                        <span class="field-error-msg" x-show="errors.name" x-text="errors.name ? errors.name[0] : ''"></span>
                                    </div>

                                    <div class="contact-field-group">
                                        <label class="contact-label" for="page-email">EMAIL ADDRESS *</label>
                                        <input type="email" id="page-email" x-model="form.email" placeholder="e.g. ayesha@example.com" class="contact-input" :class="{ 'has-error': errors.email }" required>
                                        <span class="field-error-msg" x-show="errors.email" x-text="errors.email ? errors.email[0] : ''"></span>
                                    </div>
                                </div>

                                <div class="contact-form-row">
                                    <div class="contact-field-group">
                                        <label class="contact-label" for="page-phone">PHONE / WHATSAPP NUMBER *</label>
                                        <input type="tel" id="page-phone" x-model="form.phone" placeholder="e.g. 0306 6978156" class="contact-input" :class="{ 'has-error': errors.phone }">
                                        <span class="field-error-msg" x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''"></span>
                                    </div>

                                    <!-- Fabric Preference -->
                                    <div class="contact-field-group" x-show="activeTab === 'Custom Stitching / Sizing'">
                                        <label class="contact-label" for="page-fabric">FABRIC SOURCING</label>
                                        <select id="page-fabric" x-model="fabricOption" class="contact-select">
                                            <option value="Source fabric from Leede Fusion">Source fabric from Leede Fusion</option>
                                            <option value="I will provide / ship my own fabric">I will provide / ship my own fabric</option>
                                            <option value="Need fabric consultation from tailor">Need fabric consultation from tailor</option>
                                        </select>
                                    </div>

                                    <div class="contact-field-group" x-show="activeTab !== 'Custom Stitching / Sizing'">
                                        <label class="contact-label" for="page-topic">TOPIC</label>
                                        <input type="text" id="page-topic" x-model="form.subject" class="contact-input" readonly>
                                    </div>
                                </div>

                                <!-- Sizing preference for custom design -->
                                <div class="contact-field-group" x-show="activeTab === 'Custom Stitching / Sizing'">
                                    <label class="contact-label" for="page-sizing">SIZING SPECIFICATION</label>
                                    <select id="page-sizing" x-model="sizingOption" class="contact-select">
                                        <option value="Custom Measurements (Will provide chest/waist/length)">Custom Measurements (Bespoke Made-to-Measure)</option>
                                        <option value="Standard Size (Small / Medium / Large / XL)">Standard Ready Size (S / M / L / XL)</option>
                                        <option value="Will send sample shirt for exact 1-to-1 copy">Will send sample shirt for exact 1-to-1 replication</option>
                                    </select>
                                </div>

                                <!-- Design Message -->
                                <div class="contact-field-group">
                                    <label class="contact-label" for="page-message">
                                        <span x-text="activeTab === 'Custom Stitching / Sizing' ? 'DESCRIBE YOUR DESIGN / CUTS / REQUIREMENTS *' : 'YOUR MESSAGE / INQUIRY DETAILS *'"></span>
                                    </label>
                                    <textarea
                                        id="page-message"
                                        x-model="form.message"
                                        rows="4"
                                        :placeholder="activeTab === 'Custom Stitching / Sizing'
                                            ? 'Please describe your design idea, cuts, neckline, sleeves, embroidery or embellishment notes, number of pieces, and any target date...'
                                            : 'Tell us how we can help you...'"
                                        class="contact-textarea"
                                        :class="{ 'has-error': errors.message }"
                                        required
                                    ></textarea>
                                    <span class="field-error-msg" x-show="errors.message" x-text="errors.message ? errors.message[0] : ''"></span>
                                </div>

                                <!-- Submit CTA -->
                                <button type="submit" class="contact-submit-btn" :disabled="loading">
                                    <svg x-show="loading" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="animate-spin" style="animation: spin 1s linear infinite;">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                                        <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" style="opacity: 0.75;"></path>
                                    </svg>
                                    <span x-text="loading ? 'SENDING INQUIRY...' : (activeTab === 'Custom Stitching / Sizing' ? 'SUBMIT CUSTOM DESIGN INQUIRY →' : 'SUBMIT INQUIRY →')"></span>
                                </button>
                            </form>
                        </template>

                        <!-- VIP WhatsApp Instant Fast-Track -->
                        <div class="whatsapp-atelier-callout">
                            <div class="wa-callout-text">
                                <h4>
                                    <span class="wa-cam-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                            <circle cx="12" cy="13" r="4"></circle>
                                        </svg>
                                    </span>
                                    Have Design Photos on Your Phone?
                                </h4>
                                <p>Skip typing and send your Pinterest or Instagram reference screenshots directly to our Master Tailor on WhatsApp for instant price estimate.</p>
                            </div>
                            <a
                                href="https://wa.me/{{ config('content.project.whatsapp_international', '923066978156') }}?text={{ urlencode('Hi Leede Fusion! I want to get custom clothes stitched. Here is my design picture:') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="wa-callout-btn"
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span>Send Photos on WhatsApp</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Studio Card & Interactive Map -->
                    <div id="studio-location" class="boutique-info-column reveal reveal-fade-up delay-150">
                        <div class="boutique-card">
                            <span class="boutique-card-badge">KARACHI ATELIER &amp; BOUTIQUE</span>
                            <h3 class="boutique-card-title">VISIT OR CALL OUR STUDIO</h3>

                            <div class="boutique-detail-list">
                                <div class="boutique-detail-row">
                                    <div class="boutique-detail-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="boutique-detail-label">STUDIO ADDRESS</div>
                                        <div class="boutique-detail-val">{{ config('content.project.location', 'Karachi, Sindh, Pakistan') }}</div>
                                    </div>
                                </div>

                                <div class="boutique-detail-row">
                                    <div class="boutique-detail-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="boutique-detail-label">ATELIER TIMINGS</div>
                                        <div class="boutique-detail-val">{{ config('content.project.hours', 'Mon - Sun: 9:00 AM – 9:00 PM (All 7 Days)') }}</div>
                                    </div>
                                </div>

                                <div class="boutique-detail-row">
                                    <div class="boutique-detail-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="boutique-detail-label">DIRECT PHONE &amp; EMAIL</div>
                                        <div class="boutique-detail-val">
                                            <a href="tel:{{ config('content.project.phone', '03066978156') }}" style="font-weight: 700; color: #111;">
                                                {{ config('content.project.phone', '0306 6978156') }}
                                            </a><br>
                                            <a href="mailto:{{ config('content.project.email', 'leede@gmail.com') }}" style="color: #6b7280; font-size: 13px;">
                                                {{ config('content.project.email', 'leede@gmail.com') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="https://wa.me/{{ config('content.project.whatsapp_international', '923066978156') }}?text={{ urlencode('Hi Leede Fusion! I would like to inquire about custom stitching and your collections.') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="boutique-wa-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span>Direct WhatsApp Chat</span>
                            </a>
                        </div>

                        <!-- Map Frame -->
                        <div class="map-card-wrapper" x-data="{ mapLoaded: false }">
                            <div class="skeleton skeleton-img" x-show="!mapLoaded" style="position: absolute; inset: 0; border-radius: 16px;"></div>
                            <iframe
                                src="https://maps.google.com/maps?q=Karachi,%20Pakistan&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                width="100%"
                                height="100%"
                                style="border: 0;"
                                allowfullscreen
                                loading="lazy"
                                title="Leede Fusion Studio Karachi"
                                @load="mapLoaded = true"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Frequently Asked Questions Accordion -->
        <section class="atelier-faq-wrap">
            <div class="faq-card-container reveal reveal-fade-up" x-data="{
                openFaq: 0,
                faqs: [
                    {
                        q: 'Can you stitch an outfit exactly from an Instagram or Pinterest picture?',
                        a: 'Yes, absolutely! Over 80% of our custom bespoke orders come from photo references. Our master cutters analyze the silhouette, necklines, pleating, and embroidery to reproduce the exact style tailored to your body.'
                    },
                    {
                        q: 'Do I have to provide the fabric, or do you supply it?',
                        a: 'You can choose either option. You can deliver or ship your own unstitched fabric to our Karachi workshop, or we can source premium fabrics (Swiss Lawn, Jacquard, Pure Silk, Cambric, Wash-and-Wear) on your behalf.'
                    },
                    {
                        q: 'What is the turnaround time for custom design stitching?',
                        a: 'Standard custom stitching takes 4 to 7 working days once measurements & fabric are finalized. Express 48-hour stitching is also available for urgent events.'
                    },
                    {
                        q: 'Do you take bulk production orders for independent clothing brands & boutiques?',
                        a: 'Yes! We provide full OEM apparel manufacturing, sample making, size grading, custom tagging, and bulk production runs with strict quality control for local & international fashion brands.'
                    },
                    {
                        q: 'How can I provide my measurements if I cannot visit the Karachi studio?',
                        a: 'You can select standard sizing (XS to XL), send your body measurements using our simple size chart guide via WhatsApp, or courier an old well-fitting shirt to our studio for exact 1-to-1 replication.'
                    }
                ]
            }">
                <div class="process-section-title">
                    <span>COMMON QUESTIONS</span>
                    <h3>ATELIER &amp; CUSTOM DESIGN FAQ</h3>
                </div>

                <div class="faq-grid">
                    <template x-for="(item, idx) in faqs" :key="idx">
                        <div class="faq-item">
                            <button type="button" class="faq-question" @click="openFaq = (openFaq === idx ? null : idx)">
                                <span x-text="item.q"></span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition: transform 0.25s ease;" :style="{ transform: openFaq === idx ? 'rotate(180deg)' : 'rotate(0deg)' }">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>
                            <div class="faq-answer" x-show="openFaq === idx" x-cloak x-transition.opacity.duration.200ms>
                                <p x-text="item.a"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
@endsection
