<footer class="main-footer">
    <div class="footer-top">
        <div class="footer-brand-column reveal reveal-fade-up">
            <div class="footer-logo">
                <img src="<?php echo e(asset('Logo.png')); ?>" alt="Leede Fusion logo">
                <span><span class="brand-word-primary">Leede</span> <span class="brand-word-secondary">Fusion</span></span>
            </div>
            <p class="footer-tagline">
                Premium retail clothing and accessories based in Karachi.
                Providing quality fabrics and elegant suits since our inception.
            </p>
            <div class="footer-socials">
                <a href="<?php echo e(config('content.project.whatsapp_link')); ?>">WA</a>
                <a href="#">IG</a>
                <a href="#">FB</a>
            </div>
        </div>

        <div class="footer-links-column reveal reveal-fade-up delay-100">
            <h4 class="footer-label">Collections</h4>
            <a href="<?php echo e(url('/collections')); ?>">Men's Fabric</a>
            <a href="<?php echo e(url('/collections')); ?>">Women's Suits</a>
            <a href="<?php echo e(url('/collections')); ?>">Kids Wear</a>
            <a href="<?php echo e(url('/collections')); ?>">Accessories</a>
        </div>

        <div class="footer-links-column reveal reveal-fade-up delay-200">
            <h4 class="footer-label">Info</h4>
            <a href="<?php echo e(url('/story')); ?>">Our Story</a>
            <a href="#contact">Contact Us</a>
            <a href="#contact">Location</a>
        </div>

        <div class="footer-contact-column reveal reveal-fade-up delay-300">
            <h4 class="footer-label">Contact Details</h4>
            <p><?php echo e(config('content.project.location')); ?></p>
            <p>WhatsApp: 0306 6978156</p>
            <p>Email: <?php echo e(config('content.project.email')); ?></p>
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
            © <?php echo e(date('Y')); ?> Leede Fusion. ALL RIGHTS RESERVED.
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/partials/footer.blade.php ENDPATH**/ ?>