@extends('layouts.app')

@section('title', 'Leede Fusion | Premium Fashion')

@section('content')
    @include('partials.navbar')

    <main>
        @include('sections.hero', ['heroSlides' => $heroSlides])
        @include('sections.featured-products', ['products' => $featuredProducts])
        @include('sections.categories', ['categories' => $categories])
        @include('sections.new-drops', ['products' => $latestProducts])
        @include('sections.product-focus')
        @include('sections.why-shop', ['whyShop' => $whyShop])
        @include('sections.location-map', ['project' => $project])
        @include('sections.blog', ['blogPosts' => $blogPosts])
        @include('partials.footer')
    </main>
@endsection
