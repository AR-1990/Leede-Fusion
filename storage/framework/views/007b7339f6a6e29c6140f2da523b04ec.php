<section id="contact" class="location-section">
    <div class="section-header">
        <div class="title-wrapper reveal reveal-fade-up">
            <h2 class="section-title">VISIT OUR SHOP</h2>
            <p class="section-description">
                Experience the quality in person. We are located in the heart of Karachi.
            </p>
        </div>
    </div>

    <div class="map-container reveal reveal-scale-up delay-150" x-data="{ mapLoaded: false }" style="position: relative;">
        <div class="skeleton skeleton-img" x-show="!mapLoaded" style="border-radius: var(--border-radius);"></div>
        <iframe
            src="https://maps.google.com/maps?q=Karachi,%20Pakistan&t=&z=13&ie=UTF8&iwloc=&output=embed"
            width="100%"
            height="450"
            style="border: 0;"
            allowfullscreen
            loading="lazy"
            title="Leede Fusion Location"
            @load="mapLoaded = true"
        ></iframe>
    </div>

    <div class="location-details reveal reveal-fade-up delay-250">
        <div class="detail-item">
            <h3>ADDRESS</h3>
            <p><?php echo e($project['location']); ?></p>
        </div>
        <div class="detail-item">
            <h3>HOURS</h3>
            <p>Mon - Sun: 9:00 AM – 9:00 PM</p>
        </div>
        <div class="detail-item">
            <h3>CONTACT</h3>
            <p>0306 6978156<br><?php echo e($project['email']); ?></p>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/sections/location-map.blade.php ENDPATH**/ ?>