<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Leede Admin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin Theme CSS & Scripts -->
    @vite(['resources/css/admin-theme.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { margin: 0; padding: 0; font-family: var(--font-body, 'Inter', sans-serif); }
    </style>
    @stack('styles')
</head>
<body x-data="{
    user: $store.auth.user,
    token: $store.auth.token,
    init() {
        if (!this.token) {
            const savedToken = localStorage.getItem('wahla_token');
            if (savedToken) {
                $store.auth.token = savedToken;
                this.token = savedToken;
            }
        }
    },
    async logout() {
        try {
            await fetch('/logout', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                }
            });
        } catch (e) {}
        $store.auth.logout();
        window.location.href = '/login';
    }
}">

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-link">
                    <span class="brand-name">Leede</span>
                    <span class="brand-badge">Admin</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                            <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                            <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                            <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                        </svg>
                    </span>
                    <span class="link-text">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                    </span>
                    <span class="link-text">Products</span>
                    @if(request()->routeIs('admin.products'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            <line x1="12" y1="11" x2="12" y2="17"></line>
                            <line x1="9" y1="14" x2="15" y2="14"></line>
                        </svg>
                    </span>
                    <span class="link-text">Categories</span>
                    @if(request()->routeIs('admin.categories'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </span>
                    <span class="link-text">Orders</span>
                    @if(request()->routeIs('admin.orders'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </span>
                    <span class="link-text">Users</span>
                    @if(request()->routeIs('admin.users'))
                        <div class="active-indicator"></div>
                    @endif
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="back-to-site">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Back to store</span>
                </a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="admin-main">
            <!-- Header Bar -->
            <header class="admin-header admin-header-premium">
                <div class="header-info">
                    <p class="admin-header-eyebrow">Leede Admin</p>
                    <h1 class="page-title admin-header-title">@yield('page_title', 'Dashboard')</h1>
                    <p class="admin-header-meta">@yield('page_subtitle', 'Overview and live store activity')</p>
                </div>

                <div class="admin-user-profile admin-header-user">
                    <div class="admin-user-text">
                        <span class="admin-name" x-text="$store.auth.user?.name || '{{ auth()->user()->name ?? 'Leede Admin' }}'"></span>
                        <div class="admin-user-actions">
                            <span class="admin-role-badge">Admin</span>
                            <button type="button" @click="logout()" class="admin-logout-btn">
                                Log out
                            </button>
                        </div>
                    </div>
                    <div class="admin-avatar" aria-hidden="true" x-text="($store.auth.user?.name || '{{ auth()->user()->name ?? 'L' }}').slice(0, 1).toUpperCase()">
                        {{ substr(auth()->user()->name ?? 'L', 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <div class="admin-content-scroll">
                @yield('content')
            </div>

            <!-- Leede Brand Watermark on Bottom Right -->
            <div class="main-bottom-brand" aria-hidden="true">Leede</div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
