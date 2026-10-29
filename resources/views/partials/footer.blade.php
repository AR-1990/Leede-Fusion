@php($project = config('content.project'))
<footer class="main-footer">
    <div class="footer-top">
        <div class="footer-brand-column reveal reveal-fade-up">
            <div class="footer-logo">
                <img src="{{ asset('Logo.png') }}" alt="Leede Fusion logo">
                <span><span class="brand-word-primary">Leede</span> <span class="brand-word-secondary">Fusion</span></span>
            </div>
            <p class="footer-tagline">
                Ready-to-wear articles and bespoke stitching from our Karachi atelier.
                Your idea, our craft, delivered nationwide.
            </p>
            <div class="footer-socials">
                <a href="{{ $project['whatsapp_link'] }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">WA</a>
                @if (! empty($project['instagram']))
                    <a href="{{ $project['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">IG</a>
                @endif
                @if (! empty($project['facebook']))
                    <a href="{{ $project['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">FB</a>
                @endif
                @if (! empty($project['tiktok']))
                    <a href="{{ $project['tiktok'] }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok">TT</a>
                @endif
            </div>
        </div>

        <div class="footer-links-column reveal reveal-fade-up delay-100">
            <h4 class="footer-label">Collections</h4>
            @forelse (array_slice($navCategories ?? [], 0, 5) as $cat)
                <a href="{{ $cat['url'] }}">{{ $cat['name'] }}</a>
            @empty
                <a href="{{ route('collections') }}">All Collections</a>
            @endforelse
            <a href="{{ route('design') }}">Design Your Own</a>
        </div>

        <div class="footer-links-column reveal reveal-fade-up delay-200">
            <h4 class="footer-label">Info</h4>
            <a href="{{ route('the-house') }}">Our Story</a>
            <a href="{{ route('the-process') }}">The Process</a>
            <a href="{{ route('the-fit') }}">Size Guide</a>
            <a href="{{ route('journal') }}">Journal</a>
            <a href="{{ route('track') }}">Track Your Order</a>
            <a href="{{ route('contact') }}">Contact Us</a>
        </div>

        <div class="footer-contact-column reveal reveal-fade-up delay-300">
            <h4 class="footer-label">Contact Details</h4>
            <p>{{ $project['location'] }}</p>
            <p>WhatsApp: <a href="{{ $project['whatsapp_link'] }}" target="_blank" rel="noopener noreferrer">{{ $project['phone'] }}</a></p>
            <p>Email: <a href="mailto:{{ $project['email'] }}">{{ $project['email'] }}</a></p>
            <p>Hours: {{ $project['hours'] }}</p>
        </div>
    </div>

    <div class="footer-bottom-bar reveal reveal-fade-up delay-200">
        <div class="footer-legal">
            <a href="{{ route('the-process') }}">Delivery & Process</a>
            <a href="{{ route('the-fit') }}">Sizing</a>
            <a href="{{ route('track') }}">Order Tracking</a>
        </div>
        <div class="footer-copyright">
            © {{ date('Y') }} {{ $project['name'] ?? 'Leede Fusion' }}. ALL RIGHTS RESERVED.
        </div>
    </div>
</footer>
