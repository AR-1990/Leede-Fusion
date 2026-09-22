<section id="contact" class="location-section">
    <div class="section-header">
        <div class="title-wrapper reveal reveal-fade-up">
            <h2 class="section-title">GET IN TOUCH</h2>
            <p class="section-description">
                Have questions about our fabrics, custom stitching, or collections? Send us a message or visit our boutique in Karachi.
            </p>
        </div>
    </div>

    <div class="contact-wrapper-grid">
        <!-- Contact Form Column -->
        <div class="contact-form-card reveal reveal-fade-up delay-100" x-data="{
            form: {
                name: '',
                email: '',
                phone: '',
                subject: 'General Inquiry',
                message: ''
            },
            loading: false,
            success: false,
            successMessage: '',
            errorMessage: '',
            errors: {},

            async submitForm() {
                this.loading = true;
                this.errorMessage = '';
                this.errors = {};

                try {
                    const res = await fetch('/api/contact', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await res.json();

                    if (res.ok && data.success) {
                        this.success = true;
                        this.successMessage = data.message || 'Thank you! Your message has been sent successfully.';
                        this.form = {
                            name: '',
                            email: '',
                            phone: '',
                            subject: 'General Inquiry',
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
                    <button type="button" @click="resetSuccess()" class="contact-submit-btn" style="display: inline-flex; width: auto; margin: 0 auto; padding: 10px 24px;">
                        SEND ANOTHER MESSAGE
                    </button>
                </div>
            </template>

            <!-- Active Contact Form -->
            <template x-if="!success">
                <form @submit.prevent="submitForm()" class="contact-form-fields" novalidate>
                    <div>
                        <h3 class="form-title">SEND US A MESSAGE</h3>
                        <p class="form-subtitle">Fill in the details below and our team will get back to you promptly.</p>
                    </div>

                    <!-- Error Alert -->
                    <div x-show="errorMessage" style="padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: 13px; font-weight: 500;" x-text="errorMessage"></div>

                    <div class="contact-form-row">
                        <!-- Full Name -->
                        <div class="contact-field-group">
                            <label class="contact-label" for="contact-name">FULL NAME *</label>
                            <input type="text"
                                   id="contact-name"
                                   x-model="form.name"
                                   placeholder="e.g. Intizar Wahla"
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
                            <label class="contact-label" for="contact-phone">PHONE / WHATSAPP</label>
                            <input type="tel"
                                   id="contact-phone"
                                   x-model="form.phone"
                                   placeholder="e.g. 0306 6978156"
                                   class="contact-input"
                                   :class="{ 'has-error': errors.phone }">
                            <span class="field-error-msg" x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''"></span>
                        </div>

                        <!-- Subject -->
                        <div class="contact-field-group">
                            <label class="contact-label" for="contact-subject">INQUIRY TYPE</label>
                            <select id="contact-subject"
                                    x-model="form.subject"
                                    class="contact-select">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Product & Fabric Availability">Product & Fabric Availability</option>
                                <option value="Order Status & Delivery">Order Status & Delivery</option>
                                <option value="Custom Stitching / Sizing">Custom Stitching / Sizing</option>
                                <option value="Wholesale / Bulk Orders">Wholesale / Bulk Orders</option>
                            </select>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="contact-field-group">
                        <label class="contact-label" for="contact-message">YOUR MESSAGE *</label>
                        <textarea id="contact-message"
                                  x-model="form.message"
                                  rows="4"
                                  placeholder="Tell us what you are looking for..."
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
                        <span x-text="loading ? 'SENDING MESSAGE...' : 'SEND MESSAGE'"></span>
                    </button>
                </form>
            </template>
        </div>

        <!-- Boutique Information & Location Column -->
        <div class="boutique-info-column reveal reveal-fade-up delay-200">
            <!-- Boutique Contact Card -->
            <div class="boutique-card">
                <h3 class="boutique-card-title">VISIT OUR STORE</h3>

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
                            <div class="boutique-detail-val"><?php echo e($project['location'] ?? 'Karachi, Sindh, Pakistan'); ?></div>
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
                            <div class="boutique-detail-val"><?php echo e($project['hours'] ?? 'Mon - Sun: 9:00 AM – 9:00 PM'); ?></div>
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
                                <?php echo e($project['phone'] ?? '0306 6978156'); ?><br>
                                <a href="mailto:<?php echo e($project['email'] ?? 'leede@gmail.com'); ?>" style="color: #4b5563; text-decoration: underline;">
                                    <?php echo e($project['email'] ?? 'leede@gmail.com'); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e($project['whatsapp_link'] ?? 'https://wa.me/923066978156'); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="boutique-wa-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span>Chat on WhatsApp</span>
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
                    title="Leedee Fusion Location"
                    @load="mapLoaded = true"
                ></iframe>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/location-map.blade.php ENDPATH**/ ?>