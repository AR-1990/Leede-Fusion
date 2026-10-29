@extends('layouts.app')

@section('title', 'Track Your Order | Leede Fusion')
@section('description', 'Track your Leede Fusion order or custom design request with your order reference and phone number.')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    @include('partials.navbar')

    <main class="track-page">
        <section class="track-card reveal reveal-fade-up">
            <span class="house-page-badge">ORDER TRACKING</span>
            <h1 class="studio-title">Where is my <em>outfit?</em></h1>
            <p class="track-lead">Enter the reference from your confirmation (like <strong>LF-7K2Q9M</strong> or <strong>ORD-00012</strong>) and the phone number you used.</p>

            @if ($errors->any())
                <div class="studio-errors" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('track.lookup') }}" class="track-form">
                @csrf
                <label class="studio-field">
                    <span>Order reference</span>
                    <input type="text" name="code" value="{{ old('code') }}" required maxlength="20" placeholder="LF-XXXXXX" autocapitalize="characters" style="text-transform: uppercase;">
                </label>
                <label class="studio-field">
                    <span>Phone / WhatsApp number</span>
                    <input type="tel" name="phone" value="" required maxlength="32" placeholder="03XX XXXXXXX" autocomplete="tel">
                </label>
                <button type="submit" class="studio-btn studio-btn-gold">Track order</button>
            </form>

            <p class="track-help">Lost your reference? <a href="{{ config('content.project.whatsapp_link') }}" target="_blank" rel="noopener noreferrer">Message us on WhatsApp</a>.</p>
        </section>
    </main>

    @include('partials.footer')
@endsection
