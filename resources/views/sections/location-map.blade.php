<section id="contact" class="location-section" x-data="{
    activeTab: 'Custom Stitching / Sizing',
    form: {
        name: '',
        email: '',
        phone: '',
        subject: 'Custom Stitching / Sizing',
        fabricOption: 'Source fabric from Leede Fusion',
        message: ''
    },
    loading: false,
    success: false,
    successMessage: '',
    errorMessage: '',
    errors: {},
    isHighlighted: false,

    init() {
        window.addEventListener('select-inquiry-type', (e) => {
            if (e.detail && e.detail.type) {
                this.setTab(e.detail.type);
            }
            this.highlightForm();
        });

        // Check URL parameters for direct linking
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('custom') || urlParams.has('design')) {
            this.setTab('Custom Stitching / Sizing');
            this.highlightForm();
        }
    },

    setTab(tabName) {
        this.activeTab = tabName;
        this.form.subject = tabName;
    },

    highlightForm() {
        this.isHighlighted = true;
        const el = document.getElementById('contact');
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        setTimeout(() => {
            const input = document.getElementById('contact-name') || document.getElementById('contact-message');
            if (input) input.focus();
        }, 600);
        setTimeout(() => {
            this.isHighlighted = false;
        }, 2500);
    },

    async submitForm() {
        this.loading = true;
        this.errorMessage = '';
        this.errors = {};

        // Combine fabric option into message if custom stitching is active
        let finalMessage = this.form.message;
        if (this.activeTab === 'Custom Stitching / Sizing' && this.form.fabricOption) {
            finalMessage = `[Fabric Preference: ${this.form.fabricOption}]\n\n${this.form.message}`;
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
                    subject: this.form.subject,
                    message: finalMessage
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                this.success = true;
                this.successMessage = data.message || 'Thank you! Your custom inquiry has been received. Our team will connect with you promptly.';
                this.form = {
                    name: '',
                    email: '',
                    phone: '',
                    subject: this.activeTab,
                    fabricOption: 'Source fabric from Leede Fusion',
                    message: ''
                };
            } else {
                if (data.errors) {
                    this.errors = data.errors;
                } else {
                    this.errorMessage = data.message || 'Could not send message. Please review your details and try again.';
                }
            }
        } catch (err) {
            this.errorMessage = 'Network connection issue. Please check your internet connection and try again.';
        } finally {
            this.loading = false;
        }
    },

    resetSuccess() {
        this.success = false;
        this.successMessage = '';
    }
}">
    <!-- Section Header with Design House Concept -->
    <div class="section-header">
        <div class="title-wrapper reveal reveal-fade-up">
            <span class="section-overline">ATELIER & CUSTOM CRAFTSMANSHIP</span>
            <h2 class="section-title">START YOUR DESIGN & GET IN TOUCH</h2>
            <p class="section-description">
                Have your own dress design, sketch, or reference photo? We specialize in custom bespoke tailoring and OEM manufacturing alongside our in-house collections.
            </p>
        </div>
    </div>

    <!-- Highlights Banner for Custom Design -->
    <div class="custom-features-strip reveal reveal-fade-up delay-100">
        <div class="feature-strip-item">
            <span class="feature-strip-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="6" r="3"></circle>
                    <circle cx="6" cy="18" r="3"></circle>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                    <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                    <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                </svg>
            </span>
            <div>
                <h4>Your Design & Sizing</h4>
                <p>Provide pictures, sketches, or measurements — we craft it perfectly.</p>
            </div>
        </div>
        <div class="feature-strip-item">
            <span class="feature-strip-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.3 8.7 8.7 21.3c-1 1-2.5 1-3.4 0l-2.6-2.6c-1-1-1-2.5 0-3.4L15.3 2.7c1-1 2.5-1 3.4 0l2.6 2.6c1 1 1 2.5 0 3.4Z"></path>
                    <path d="m14.5 3.5 2 2"></path>
                    <path d="m11.5 6.5 2 2"></path>
                    <path d="m8.5 9.5 2 2"></path>
                    <path d="m5.5 12.5 2 2"></path>
                </svg>
            </span>
            <div>
                <h4>Finest Fabrics</h4>
                <p>Pure lawns, luxury silks, cambrics, wash-and-wear, or your provided fabric.</p>
            </div>
        </div>
        <div class="feature-strip-item">
            <span class="feature-strip-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </span>
            <div>
                <h4>Single & Bulk Orders</h4>
                <p>From single bespoke bridal/pret outfits to wholesale brand production.</p>
            </div>
        </div>
    </div>

    <div class="contact-wrapper-grid">
        <!-- Contact & Custom Design Form Column -->
        <div class="contact-form-card reveal reveal-fade-up delay-150" :class="{ 'card-highlighted': isHighlighted }">
            
            <!-- Inquiry Type Selector Chips -->
            <div class="inquiry-type-selector">
                <span class="selector-label">SELECT YOUR INQUIRY TYPE:</span>
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
                        <span>Custom Design / Stitching</span>
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
                        <span>Ready Products & Lawn</span>
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
                        <span>B2B & Bulk Production</span>
                    </button>
                    <button
                        type="button"
                        class="inquiry-chip-btn"
                        :class="{ 'is-selected': activeTab === 'General Inquiry' }"
                        @click="setTab('General Inquiry')"
                    >
                        <svg class="chip-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>General & Boutique Visit</span>
                    </button>
                </div>
            </div>

            <!-- Contextual Banner when Custom Stitching is Active -->
            <div x-show="activeTab === 'Custom Stitching / Sizing'" class="custom-atelier-notice" x-transition>
                <div class="notice-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3"></circle>
                        <circle cx="6" cy="18" r="3"></circle>
                        <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                        <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                        <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div class="notice-content">
                    <h5>Bringing Your Own Design?</h5>
                    <p>Describe your design below (cuts, embroidery, style, sizing) or tap below to send design photos directly to our master tailor on WhatsApp.</p>
                    <a
                        :href="'https://wa.me/{{ $project['whatsapp_international'] ?? '923066978156' }}?text=' + encodeURIComponent('Hi Leede Fusion! I have a custom design and want to get clothes stitched. Here are the details:')"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="notice-wa-link"
                    >
                        <span>Send Design Photos on WhatsApp &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Success Notification State -->
            <template x-if="success">
                <div class="contact-success-banner">
                    <div class="contact-success-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <h3 class="contact-success-title">MESSAGE RECEIVED</h3>
                    <p class="contact-success-desc" x-text="successMessage"></p>
                    <button type="button" @click="resetSuccess()" class="contact-submit-btn" style="display: inline-flex; width: auto; margin: 0 auto; padding: 12px 28px;">
                        SEND ANOTHER INQUIRY
                    </button>
                </div>
            </template>

            <!-- Active Contact Form -->
            <template x-if="!success">
                <form @submit.prevent="submitForm()" class="contact-form-fields" novalidate>
                    <!-- Error Alert -->
                    <div x-show="errorMessage" style="padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: 13px; font-weight: 500;" x-text="errorMessage"></div>

                    <div class="contact-form-row">
                        <!-- Full Name -->
                        <div class="contact-field-group">
                            <label class="contact-label" for="contact-name">FULL NAME *</label>
                            <input type="text"
                                   id="contact-name"
                                   x-model="form.name"
                                   placeholder="e.g. Fatima Ali / Intizar"
                                   class="contact-input"
                                   :class="{ 'has-error': errors.name }"
                                   required>
                            <span class="field-error-msg" x-show="errors.name" x-text="errors.name ? errors.name[0] : ''"></span>
                        </div>

                        <!-- Email -->
                        <div class="contact-field-group">
                            <label class="contact-label" for="contact-email">EMAIL ADDRESS *</label>
                            <input type="email"
                                   id="contact-email"
                                   x-model="form.email"
                                   placeholder="e.g. you@example.com"
                                   class="contact-input"
                                   :class="{ 'has-error': errors.email }"
                                   required>
                            <span class="field-error-msg" x-show="errors.email" x-text="errors.email ? errors.email[0] : ''"></span>
                        </div>
                    </div>

                    <div class="contact-form-row">
                        <!-- Phone / WhatsApp -->
                        <div class="contact-field-group">
                            <label class="contact-label" for="contact-phone">PHONE / WHATSAPP NUMBER *</label>
                            <input type="tel"
                                   id="contact-phone"
                                   x-model="form.phone"
                                   placeholder="e.g. 0306 6978156"
                                   class="contact-input"
                                   :class="{ 'has-error': errors.phone }">
                            <span class="field-error-msg" x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''"></span>
                        </div>

                        <!-- Fabric Preference (Visible for Custom Stitching) -->
                        <div class="contact-field-group" x-show="activeTab === 'Custom Stitching / Sizing'">
                            <label class="contact-label" for="contact-fabric">FABRIC PREFERENCE</label>
                            <select id="contact-fabric"
                                    x-model="form.fabricOption"
                                    class="contact-select">
                                <option value="Source fabric from Leede Fusion">Source fabric from Leede Fusion</option>
                                <option value="I will provide my own fabric">I will provide my own fabric</option>
                                <option value="Need consultation on fabric selection">Need consultation on fabric</option>
                            </select>
                        </div>

                        <!-- Subject selection for non-custom tabs -->
                        <div class="contact-field-group" x-show="activeTab !== 'Custom Stitching / Sizing'">
                            <label class="contact-label" for="contact-subject">TOPIC</label>
                            <input type="text"
                                   id="contact-subject"
                                   x-model="form.subject"
                                   class="contact-input"
                                   readonly>
                        </div>
                    </div>

                    <!-- Message / Design Specifications -->
                    <div class="contact-field-group">
                        <label class="contact-label" for="contact-message">
                            <span x-text="activeTab === 'Custom Stitching / Sizing' ? 'DESCRIBE YOUR DESIGN / ATTIRE REQUIREMENTS *' : 'YOUR MESSAGE *'"></span>
                        </label>
                        <textarea id="contact-message"
                                  x-model="form.message"
                                  rows="4"
                                  :placeholder="activeTab === 'Custom Stitching / Sizing'
                                      ? 'Tell us about the design, cut, embroidery, measurements, number of pieces, or any specific style details you want...'
                                      : 'Write your message or inquiry here...'"
                                  class="contact-textarea"
                                  :class="{ 'has-error': errors.message }"
                                  required></textarea>
                        <span class="field-error-msg" x-show="errors.message" x-text="errors.message ? errors.message[0] : ''"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="contact-submit-btn" :disabled="loading">
                        <svg x-show="loading" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="animate-spin" style="animation: spin 1s linear infinite;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" style="opacity: 0.75;"></path>
                        </svg>
                        <span x-text="loading ? 'PROCESSING INQUIRY...' : (activeTab === 'Custom Stitching / Sizing' ? 'SUBMIT CUSTOM DESIGN INQUIRY →' : 'SEND INQUIRY →')"></span>
                    </button>
                </form>
            </template>
        </div>

        <!-- Boutique Information & Location Column -->
        <div class="boutique-info-column reveal reveal-fade-up delay-200">
            <!-- Boutique Contact Card -->
            <div class="boutique-card">
                <div class="boutique-card-badge">DIRECT ATELIER ACCESS</div>
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
                            <div class="boutique-detail-label">STORE LOCATION</div>
                            <div class="boutique-detail-val">{{ $project['location'] ?? 'Karachi, Sindh, Pakistan' }}</div>
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
                            <div class="boutique-detail-label">BUSINESS HOURS</div>
                            <div class="boutique-detail-val">{{ $project['hours'] ?? 'Mon - Sun: 9:00 AM – 9:00 PM' }}</div>
                        </div>
                    </div>

                    <div class="boutique-detail-row">
                        <div class="boutique-detail-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="boutique-detail-label">DIRECT PHONE & EMAIL</div>
                            <div class="boutique-detail-val">
                                <a href="tel:{{ $project['phone'] ?? '03066978156' }}" style="font-weight: 600; color: #111;">
                                    {{ $project['phone'] ?? '0306 6978156' }}
                                </a><br>
                                <a href="mailto:{{ $project['email'] ?? 'leede@gmail.com' }}" style="color: #6b7280; font-size: 13px;">
                                    {{ $project['email'] ?? 'leede@gmail.com' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ $project['whatsapp_link'] ?? 'https://wa.me/923066978156' }}?text={{ urlencode('Hi Leede Fusion! I would like to inquire about custom stitching and your collections.') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="boutique-wa-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span>Instant WhatsApp Chat</span>
                </a>
            </div>

            <!-- Compact Map Card -->
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
</section>
