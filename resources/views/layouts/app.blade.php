<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php($project = config('content.project'))
    <title>@yield('title', 'Leede Fusion | Ready-to-Wear & Bespoke Stitching in Karachi')</title>
    <meta name="description" content="@yield('description', 'Leede Fusion, Karachi: ready-to-wear articles, bespoke stitching, custom designs and abayas. Order on WhatsApp with nationwide delivery.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="icon" type="image/png" href="{{ asset('Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('Logo.png') }}">
    <meta name="theme-color" content="#0d0d10">

    <meta property="og:site_name" content="Leede Fusion">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Leede Fusion | Ready-to-Wear & Bespoke Stitching in Karachi')">
    <meta property="og:description" content="@yield('description', 'Leede Fusion, Karachi: ready-to-wear articles, bespoke stitching, custom designs and abayas. Order on WhatsApp with nationwide delivery.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url(View::yieldContent('og_image', '/hero-fashion.webp')) }}">
    <meta name="twitter:card" content="summary_large_image">

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'ClothingStore',
            'name' => 'Leede Fusion',
            'url' => url('/'),
            'logo' => asset('Logo.png'),
            'image' => url('/hero-fashion.webp'),
            'telephone' => '+'.$project['whatsapp_international'],
            'email' => $project['email'],
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Karachi', 'addressRegion' => 'Sindh', 'addressCountry' => 'PK'],
            'openingHours' => 'Mo-Su 09:00-21:00',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-heading: 'Bebas Neue', sans-serif;
            --font-serif: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
            --font-body: 'Inter', sans-serif;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/storefront.css') }}?v={{ @filemtime(public_path('css/storefront.css')) }}">
        <script src="{{ asset('js/app.js') }}?v={{ @filemtime(public_path('js/app.js')) }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js" defer></script>
    @endif
    @stack('head')
</head>
<body class="antialiased @yield('body_class')"
      x-data
      @keydown.escape.window="$dispatch('close-quick-view')">
    @include('partials.preloader')

    @yield('content')

    @unless($hideWhatsApp ?? false)
        @include('partials.whatsapp')
    @endunless

    @include('partials.quick-view-modal')

    @stack('scripts')
</body>
</html>
