@extends('layouts.app')

@section('title', 'The Process: How Your Bespoke Outfit Is Made | Leede Fusion Karachi')
@section('description', 'From your idea to your doorstep: consultation, fabric selection, measurements, stitching, fitting and nationwide delivery. See how Leede Fusion creates every custom outfit.')

@section('content')
    @include('partials.navbar')

    <div class="the-house-page">
        <header class="house-hero">
            <div class="house-hero-inner reveal reveal-fade-up">
                <span class="house-page-badge">HOW WE WORK</span>
                <h1 class="house-main-title">THE PROCESS</h1>
                <p class="house-main-subtitle">
                    Six clear steps from your idea to a finished outfit, whether you pick a ready article or design one from scratch.
                </p>
            </div>
        </header>

        <main class="house-narrative-container">
            <section class="house-pillars-section reveal reveal-fade-up">
                <div class="pillars-header">
                    <span class="pillars-tag">IDEA TO DOORSTEP</span>
                    <h3>SIX STEPS OF EVERY ORDER</h3>
                </div>

                @php($steps = [
                    ['Consultation', 'Share your idea on WhatsApp or the contact form: a Pinterest screenshot, a sketch, or a ready article you want customized.'],
                    ['Fabric & Design', 'We suggest fabric weight, colour and embellishment, then confirm the design details and a clear price before work begins.'],
                    ['Measurements', 'Send your measurements using our fit guide, share a well-fitting garment, or visit the studio in Karachi.'],
                    ['Cutting & Stitching', 'Your pattern is drafted to your body and stitched by our master tailors, with embroidery or finishing as agreed.'],
                    ['Quality Check', 'Every seam, hem and finish is inspected and the outfit is steam-pressed and packed with care.'],
                    ['Delivery', 'Free studio pickup in Karachi, or nationwide delivery through trusted couriers. Small alterations after delivery are on us.'],
                ])
                <div class="pillars-grid">
                    @foreach ($steps as [$name, $text])
                        <div class="pillar-item">
                            <div class="pillar-icon">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                            <h4>{{ $name }}</h4>
                            <p>{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <hr class="house-divider">

            <section class="house-chapter reveal reveal-fade-up">
                <div class="chapter-text">
                    <span class="chapter-number">READY OR BESPOKE</span>
                    <h2 class="chapter-title">TWO WAYS TO ORDER</h2>
                    <div class="chapter-prose">
                        <p class="lead-text">
                            <strong>Ready-to-wear articles</strong> ship within days. Choose your size from the collection and order on WhatsApp.
                        </p>
                        <p>
                            <strong>Customized and bespoke pieces</strong> usually take 7 to 14 days depending on embroidery and season. Any article from our collection can also be altered: a different length, sleeves, neckline or colour.
                        </p>
                    </div>
                </div>
            </section>

            <div class="house-cta-wrapper reveal reveal-fade-up">
                <a href="{{ route('design') }}" class="house-primary-cta-btn">
                    START YOUR DESIGN
                </a>
            </div>
        </main>
    </div>

    @include('partials.footer')
@endsection
