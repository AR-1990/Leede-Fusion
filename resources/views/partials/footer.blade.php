<footer class="main-footer">
    <div class="footer-top">
        <div class="footer-brand-column reveal reveal-fade-up">
            <div class="footer-logo">Leede</div>
            <p class="footer-tagline">
                Premium retail clothing and accessories based in Karachi.
                Providing quality fabrics and elegant suits since our inception.
            </p>
            <div class="footer-socials">
                <a href="{{ config('content.project.whatsapp_link') }}">WA</a>
                <a href="#">IG</a>
                <a href="#">FB</a>
            </div>
        </div>

        <div class="footer-links-column reveal reveal-fade-up delay-100">
            <h4 class="footer-label">Collections</h4>
            <a href="{{ url('/collections') }}">Men's Fabric</a>
            <a href="{{ url('/collections') }}">Women's Suits</a>
            <a href="{{ url('/collections') }}">Kids Wear</a>
            <a href="{{ url('/collections') }}">Accessories</a>
        </div>

        <div class="footer-links-column reveal reveal-fade-up delay-200">
            <h4 class="footer-label">Info</h4>
            <a href="{{ url('/story') }}">Our Story</a>
            <a href="#contact">Contact Us</a>
            <a href="#contact">Location</a>
        </div>

        <div class="footer-contact-column reveal reveal-fade-up delay-300">
            <h4 class="footer-label">Contact Details</h4>
            <p>{{ config('content.project.location') }}</p>
            <p>WhatsApp: 0306 6978156</p>
            <p>Email: {{ config('content.project.email') }}</p>
            <p>Hours: 9:00 AM – 9:00 PM</p>
        </div>
    </div>

    <div class="footer-bottom-bar reveal reveal-fade-up delay-200">
        <div class="footer-legal">
            <span>Privacy Policy</span>
            <span>Terms of Service</span>
            <span>Cookies</span>
        </div>
        <div class="footer-copyright">
            © {{ date('Y') }} Leede. ALL RIGHTS RESERVED.
        </div>
    </div>
</footer>
