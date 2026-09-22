<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Leede | Premium Fashion')</title>
    <meta name="description" content="@yield('description', 'Community-driven streetwear and lifestyle brand.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-heading: 'Bebas Neue', sans-serif;
            --font-body: 'Inter', sans-serif;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
