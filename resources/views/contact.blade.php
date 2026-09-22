@extends('layouts.app')

@section('title', 'Contact Us | Leedee Fusion')

@section('content')
    @include('partials.navbar')

    <main style="padding-top: 20px;">
        @include('sections.location-map', ['project' => config('content.project')])
    </main>

    @include('partials.footer')
@endsection
