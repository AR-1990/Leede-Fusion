<?php $__env->startSection('title', 'Sign In / Register | Leede'); ?>

<?php $__env->startSection('body_class', 'auth-page-body'); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-page" x-data="{
        mode: 'login', // 'login' | 'register'
        
        // Login fields
        loginEmail: '',
        loginPassword: '',
        
        // Register fields
        regName: '',
        regEmail: '',
        regPassword: '',
        regPasswordConfirmation: '',
        
        // States
        error: '',
        success: '',
        fieldErrors: {},
        loading: false,

        switchTab(newMode) {
            this.mode = newMode;
            this.error = '';
            this.success = '';
            this.fieldErrors = {};
            this.loginEmail = '';
            this.loginPassword = '';
            this.regName = '';
            this.regEmail = '';
            this.regPassword = '';
            this.regPasswordConfirmation = '';
        },

        clearFieldError(field) {
            if (this.fieldErrors[field]) {
                const copy = { ...this.fieldErrors };
                delete copy[field];
                this.fieldErrors = copy;
            }
            if (Object.keys(this.fieldErrors).length === 0) {
                this.error = '';
            }
        },

        async submitLogin() {
            this.error = '';
            this.success = '';
            this.fieldErrors = {};
            this.loading = true;

            try {
                const res = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                    },
                    body: JSON.stringify({
                        email: this.loginEmail,
                        password: this.loginPassword
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    if (data.errors && typeof data.errors === 'object') {
                        this.fieldErrors = data.errors;
                        const messages = Object.values(data.errors).flat();
                        this.error = messages.join(' • ');
                    } else if (data.message) {
                        this.error = data.message;
                    } else {
                        this.error = 'Invalid credentials. Please verify your email and password.';
                    }
                    this.loading = false;
                    return;
                }

                const token = data.access_token || data.token;
                if (token && data.user) {
                    $store.auth.user = data.user;
                    $store.auth.token = token;
                    $store.auth.persist();
                    const isAdmin = !!data.user.is_admin;
                    this.success = isAdmin ? `Welcome, Administrator! Redirecting to Admin Panel...` : `Welcome back, ${data.user.name}! Redirecting...`;
                    setTimeout(() => {
                        window.location.href = isAdmin ? '/admin' : '/';
                    }, 700);
                } else {
                    this.error = 'Authentication succeeded but received an invalid response.';
                }
            } catch (e) {
                this.error = 'Network error or server unreachable. Please try again.';
            }
            this.loading = false;
        },

        async submitRegister() {
            this.error = '';
            this.success = '';
            this.fieldErrors = {};

            // Client-side pre-validation
            if (this.regPassword.length < 8) {
                this.fieldErrors = { password: ['The password must be at least 8 characters.'] };
                this.error = 'The password must be at least 8 characters.';
                return;
            }

            if (this.regPassword !== this.regPasswordConfirmation) {
                this.fieldErrors = { password_confirmation: ['Password confirmation does not match.'] };
                this.error = 'Password confirmation does not match.';
                return;
            }

            this.loading = true;

            try {
                const res = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                    },
                    body: JSON.stringify({
                        name: this.regName,
                        email: this.regEmail,
                        password: this.regPassword,
                        password_confirmation: this.regPasswordConfirmation
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    if (data.errors && typeof data.errors === 'object') {
                        this.fieldErrors = data.errors;
                        const messages = Object.values(data.errors).flat();
                        this.error = messages.length > 0 ? messages.join(' • ') : 'Validation failed. Please correct the fields below.';
                    } else if (data.message) {
                        this.error = data.message;
                    } else {
                        this.error = 'Registration failed. Please check your information.';
                    }
                    this.loading = false;
                    return;
                }

                const token = data.access_token || data.token;
                if (token && data.user) {
                    $store.auth.user = data.user;
                    $store.auth.token = token;
                    $store.auth.persist();
                    this.success = `Account created successfully! Welcome to Leede, ${data.user.name}. Redirecting...`;
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1200);
                } else {
                    this.mode = 'login';
                    this.loginEmail = this.regEmail;
                    this.success = 'Account created successfully! Please sign in with your credentials.';
                }
            } catch (e) {
                this.error = 'Network error or server unreachable. Please try again.';
            }
            this.loading = false;
        }
    }">
        <div class="auth-container">
            <div class="auth-watermark">LEEDE</div>
            <div class="auth-card reveal reveal-fade-up">
                <a href="<?php echo e(route('home')); ?>" class="auth-logo">LEEDE</a>
                
                <h1 class="auth-title" x-text="mode === 'register' ? 'JOIN LEEDE' : 'WELCOME BACK'"></h1>
                <p class="auth-subtitle" x-text="mode === 'register' ? 'Create your account to unlock bespoke tailoring & new drops' : 'Enter your credentials to access your account'"></p>

                <!-- Navigation Tabs -->
                <div class="auth-tabs">
                    <button type="button" 
                            class="auth-tab-btn" 
                            :class="{ 'active': mode === 'login' }"
                            @click="switchTab('login')">
                        SIGN IN
                    </button>
                    <button type="button" 
                            class="auth-tab-btn" 
                            :class="{ 'active': mode === 'register' }"
                            @click="switchTab('register')">
                        CREATE ACCOUNT
                    </button>
                </div>

                <?php if(session('error')): ?>
                    <div class="auth-alert auth-alert-error">
                        <svg class="auth-alert-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div class="auth-alert-content">
                            <div class="auth-alert-title">Notice</div>
                            <div><?php echo e(session('error')); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="auth-alert auth-alert-success">
                        <svg class="auth-alert-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <div class="auth-alert-content">
                            <div class="auth-alert-title">Success</div>
                            <div><?php echo e(session('success')); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- API / Validation Error Banner -->
                <div x-show="error" class="auth-alert auth-alert-error" x-cloak>
                    <svg class="auth-alert-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="auth-alert-content">
                        <div class="auth-alert-title">Please review the following:</div>
                        <div x-text="error"></div>
                    </div>
                </div>

                <!-- Success Alert Banner -->
                <div x-show="success" class="auth-alert auth-alert-success" x-cloak>
                    <svg class="auth-alert-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="auth-alert-content">
                        <div class="auth-alert-title">Success</div>
                        <div x-text="success"></div>
                    </div>
                </div>

                <!-- 1. LOGIN FORM -->
                <form x-show="mode === 'login'" class="auth-form" @submit.prevent="submitLogin()" autocomplete="on">
                    <div class="form-group">
                        <label>EMAIL ADDRESS</label>
                        <input type="email" 
                               name="email"
                               x-model="loginEmail" 
                               @input="clearFieldError('email')"
                               :class="{ 'has-error': fieldErrors.email }"
                               placeholder="you@domain.com" 
                               autocomplete="username email"
                               required>
                        <template x-if="fieldErrors.email">
                            <div class="field-error-msg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span x-text="fieldErrors.email[0]"></span>
                            </div>
                        </template>
                    </div>

                    <div class="form-group">
                        <label>PASSWORD</label>
                        <input type="password" 
                               name="password"
                               x-model="loginPassword" 
                               @input="clearFieldError('password')"
                               :class="{ 'has-error': fieldErrors.password }"
                               placeholder="••••••••" 
                               autocomplete="current-password"
                               required>
                        <template x-if="fieldErrors.password">
                            <div class="field-error-msg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span x-text="fieldErrors.password[0]"></span>
                            </div>
                        </template>
                    </div>

                    <button type="submit" 
                            class="auth-submit-btn" 
                            :disabled="loading" 
                            x-text="loading ? 'AUTHENTICATING...' : 'SIGN IN'">
                    </button>
                </form>

                <!-- 2. REGISTER FORM (Independent inputs & autocomplete prevent autofill pollution) -->
                <form x-show="mode === 'register'" class="auth-form" @submit.prevent="submitRegister()" autocomplete="off" x-cloak>
                    <div class="form-group">
                        <label>FULL NAME</label>
                        <input type="text" 
                               name="register_name"
                               x-model="regName" 
                               @input="clearFieldError('name')"
                               :class="{ 'has-error': fieldErrors.name }"
                               placeholder="e.g. Muhammad Wahla" 
                               autocomplete="name"
                               required>
                        <template x-if="fieldErrors.name">
                            <div class="field-error-msg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span x-text="fieldErrors.name[0]"></span>
                            </div>
                        </template>
                    </div>

                    <div class="form-group">
                        <label>EMAIL ADDRESS</label>
                        <input type="email" 
                               name="register_email"
                               x-model="regEmail" 
                               @input="clearFieldError('email')"
                               :class="{ 'has-error': fieldErrors.email }"
                               placeholder="you@domain.com" 
                               autocomplete="email"
                               required>
                        <template x-if="fieldErrors.email">
                            <div class="field-error-msg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span x-text="fieldErrors.email[0]"></span>
                            </div>
                        </template>
                    </div>

                    <div class="form-group">
                        <label>PASSWORD</label>
                        <input type="password" 
                               name="register_password"
                               x-model="regPassword" 
                               @input="clearFieldError('password')"
                               :class="{ 'has-error': fieldErrors.password }"
                               placeholder="Minimum 8 characters" 
                               autocomplete="new-password"
                               required>
                        <span class="field-hint">Must be at least 8 characters.</span>
                        <template x-if="fieldErrors.password">
                            <div class="field-error-msg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span x-text="fieldErrors.password[0]"></span>
                            </div>
                        </template>
                    </div>

                    <div class="form-group">
                        <label>CONFIRM PASSWORD</label>
                        <input type="password" 
                               name="register_password_confirmation"
                               x-model="regPasswordConfirmation" 
                               @input="clearFieldError('password_confirmation')"
                               :class="{ 'has-error': fieldErrors.password_confirmation }"
                               placeholder="Re-enter password" 
                               autocomplete="new-password"
                               required>
                        <template x-if="fieldErrors.password_confirmation">
                            <div class="field-error-msg">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span x-text="fieldErrors.password_confirmation[0]"></span>
                            </div>
                        </template>
                    </div>

                    <button type="submit" 
                            class="auth-submit-btn" 
                            :disabled="loading" 
                            x-text="loading ? 'CREATING ACCOUNT...' : 'REGISTER ACCOUNT'">
                    </button>
                </form>

                <div class="auth-footer-text">
                    <span x-text="mode === 'register' ? 'Already have an account?' : 'Don\'t have an account?'"></span>
                    <a href="#" @click.prevent="switchTab(mode === 'register' ? 'login' : 'register')" x-text="mode === 'register' ? 'Sign In' : 'Create Account'"></a>
                </div>
            </div>
        </div>

        <div class="auth-image-side"></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/login.blade.php ENDPATH**/ ?>