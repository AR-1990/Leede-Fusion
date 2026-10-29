<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Leede Fusion Admin</title>

    <!-- Google Fonts: same families as the storefront (Playfair headings, Bebas labels, Inter body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <meta name="theme-color" content="#0d0d10">
    <link rel="icon" type="image/png" href="{{ asset('Logo.png') }}">

    <!-- Admin Theme CSS & Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/admin-theme.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}?v={{ @filemtime(public_path('css/admin-theme.css')) }}">
        <script src="{{ asset('js/app.js') }}?v={{ @filemtime(public_path('js/app.js')) }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js" defer></script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body { margin: 0; padding: 0; font-family: var(--font-body, 'Inter', sans-serif); }
    </style>
    @stack('styles')
    <script>
        // Shared image upload for admin forms (session-authenticated, returns the public URL).
        window.adminUpload = async function (file, folder = 'gallery') {
            if (!file) throw new Error('No file selected.');
            const data = new FormData();
            data.append('file', file);
            data.append('folder', folder);
            const res = await fetch(@js(route('admin.media')), {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: data,
            });
            const json = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(json.message || 'Upload failed.');
            return json.url;
        };
    </script>

    <!-- Server-side tables (Yajra DataTables). Admin only; never loaded on the storefront. -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        /**
         * adminDataTable('#table', url, columns, { order: [[col, 'desc']], params: () => ({...}), emptyText, onData(json) })
         * Row buttons carry data-action / data-id; a click fires window event "dt-<action>" with { id }.
         */
        window.adminDataTable = function (selector, url, columns, options = {}) {
            const $table = jQuery(selector);
            const table = $table.DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url,
                    data: (d) => Object.assign(d, typeof options.params === 'function' ? options.params() : {}),
                    dataSrc: (json) => {
                        if (typeof options.onData === 'function') options.onData(json);
                        return json.data;
                    },
                    error: (xhr) => {
                        if (xhr.status === 401 || xhr.status === 419) window.location.reload();
                    },
                },
                columns,
                order: options.order || [[0, 'desc']],
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                scrollX: true,
                autoWidth: false,
                searchDelay: 350,
                language: {
                    search: '',
                    searchPlaceholder: 'Search…',
                    lengthMenu: '_MENU_ per page',
                    info: '_START_–_END_ of _TOTAL_',
                    infoEmpty: 'No records',
                    infoFiltered: '(filtered from _MAX_)',
                    emptyTable: options.emptyText || 'Nothing here yet.',
                    zeroRecords: 'No matching records.',
                    processing: '<span class="dt-gold-spinner" aria-label="Loading"></span>',
                    paginate: { first: '«', previous: '‹', next: '›', last: '»' },
                },
            });

            $table.on('click', '[data-action]', function (event) {
                event.preventDefault();
                window.dispatchEvent(new CustomEvent('dt-' + this.dataset.action, { detail: { id: Number(this.dataset.id) } }));
            });

            return table;
        };

        // Fetch one record (product, category, order, inquiry) for the edit/detail panels.
        window.adminRecord = async function (type, id) {
            const res = await fetch(`/admin/data/${type}/${id}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Could not load this record.');
            return res.json();
        };
    </script>
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
                    <img src="{{ asset('Logo.png') }}" alt="Leede Fusion logo" class="brand-logo-image">
                    <span class="brand-name"><span class="brand-word-primary">Leede</span> <span class="brand-word-secondary">Fusion</span></span>
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

                <a href="{{ route('admin.inquiries') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries*') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </span>
                    <span class="link-text" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <span>Inquiries</span>
                        @if(!empty($unreadInquiriesCount) && $unreadInquiriesCount > 0)
                            <span class="sidebar-badge" style="background: #ef4444; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 9999px; line-height: 1.4;">{{ $unreadInquiriesCount }}</span>
                        @endif
                    </span>
                    @if(request()->routeIs('admin.inquiries*'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.posts.index') }}" class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                    </span>
                    <span class="link-text">Journal</span>
                    @if(request()->routeIs('admin.posts.*'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.custom-orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.custom-orders.*') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="6" cy="6" r="3"></circle>
                            <circle cx="6" cy="18" r="3"></circle>
                            <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                            <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                            <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                        </svg>
                    </span>
                    <span class="link-text" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <span>Custom Orders</span>
                        @if(!empty($newCustomOrdersCount))
                            <span class="sidebar-badge" style="background: #d4af37; color: #0d0d10; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 9999px; line-height: 1.4;">{{ $newCustomOrdersCount }}</span>
                        @endif
                    </span>
                    @if(request()->routeIs('admin.custom-orders.*'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.design-options.index') }}" class="sidebar-link {{ request()->routeIs('admin.design-options.*') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"></path>
                        </svg>
                    </span>
                    <span class="link-text">Design Studio</span>
                    @if(request()->routeIs('admin.design-options.*'))
                        <div class="active-indicator"></div>
                    @endif
                </a>

                <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <span class="link-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </span>
                    <span class="link-text">Site Settings</span>
                    @if(request()->routeIs('admin.settings*'))
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
                    <p class="admin-header-eyebrow">Leede Fusion Admin</p>
                    <h1 class="page-title admin-header-title">@yield('page_title', 'Dashboard')</h1>
                    <p class="admin-header-meta">@yield('page_subtitle', 'Overview and live store activity')</p>
                </div>

                <div class="admin-user-profile admin-header-user">
                    <div class="admin-user-text">
                        <span class="admin-name" x-text="$store.auth.user?.name || '{{ auth()->user()->name ?? 'Leede Fusion Admin' }}'"></span>
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

            <!-- Leede Fusion Brand Watermark on Bottom Right -->
            <div class="main-bottom-brand" aria-hidden="true">Leede Fusion</div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
