@extends('layouts.app')

@section('title', 'Leede Fusion | Ready-to-Wear & Bespoke Stitching in Karachi')
@section('description', 'Shop ready-to-wear articles or design your own outfit with Leede Fusion, Karachi: custom stitching, abayas and festive wear with nationwide delivery.')

@section('content')
    @include('partials.navbar')

    <main>
        @include('sections.hero', ['heroSlides' => $heroSlides])
        @include('sections.essence')
        @include('sections.house-story')
        @include('sections.featured-products', ['products' => $featuredProducts])
        @include('sections.categories', ['categories' => $categories])
        @include('sections.new-drops', ['products' => $latestProducts])
        @include('sections.product-focus')
        @include('sections.why-shop', ['whyShop' => $whyShop])
        @include('sections.blog', ['blogPosts' => $blogPosts])
        @include('partials.footer')
    </main>
@endsection
