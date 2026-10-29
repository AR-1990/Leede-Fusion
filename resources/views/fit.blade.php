@extends('layouts.app')

@section('title', 'The Fit: Measurement & Size Guide | Leede Fusion Karachi')
@section('description', 'How to take your measurements for bespoke stitching at Leede Fusion, plus our standard size chart for ready-to-wear articles.')

@section('content')
    @include('partials.navbar')

    <div class="the-house-page">
        <header class="house-hero">
            <div class="house-hero-inner reveal reveal-fade-up">
                <span class="house-page-badge">SIZE & MEASUREMENT GUIDE</span>
                <h1 class="house-main-title">THE FIT</h1>
                <p class="house-main-subtitle">
                    A perfect fit starts with the right numbers. Here is how to measure, and our standard sizes for ready articles.
                </p>
            </div>
        </header>

        <main class="house-narrative-container">
            <section class="house-pillars-section reveal reveal-fade-up">
                <div class="pillars-header">
                    <span class="pillars-tag">HOW TO MEASURE</span>
                    <h3>TAKE YOUR MEASUREMENTS</h3>
                </div>

                @php($measures = [
                    ['Chest / Bust', 'Around the fullest part of the chest, tape level under the arms.'],
                    ['Waist', 'Around the natural waist, the narrowest part of the torso.'],
                    ['Hips', 'Around the fullest part of the hips, feet together.'],
                    ['Shoulder', 'From one shoulder point to the other across the back.'],
                    ['Sleeve Length', 'From the shoulder point down to where you want the sleeve to end.'],
                    ['Shirt Length', 'From the highest shoulder point down to the desired hem.'],
                    ['Armhole', 'Around the arm where it meets the shoulder, not too tight.'],
                    ['Trouser Length', 'From the waist down to the ankle or desired length.'],
                ])
                <div class="pillars-grid">
                    @foreach ($measures as [$name, $text])
                        <div class="pillar-item">
                            <div class="pillar-icon">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                            <h4>{{ $name }}</h4>
                            <p>{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="fit-note">Tip: measure over light clothing and keep the tape snug, not tight. Not sure? Send us a garment that fits you well and we will copy its measurements.</p>
            </section>

            <hr class="house-divider">

            <section class="house-chapter reveal reveal-fade-up">
                <span class="chapter-number">READY-TO-WEAR</span>
                <h2 class="chapter-title">STANDARD SIZE CHART (INCHES)</h2>
                <div class="fit-table-wrap">
                    <table class="fit-table">
                        <thead>
                            <tr><th>Size</th><th>Chest</th><th>Waist</th><th>Hips</th><th>Shirt Length</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>XS</td><td>34</td><td>30</td><td>38</td><td>40</td></tr>
                            <tr><td>S</td><td>36</td><td>32</td><td>40</td><td>41</td></tr>
                            <tr><td>M</td><td>38</td><td>34</td><td>42</td><td>42</td></tr>
                            <tr><td>L</td><td>40</td><td>36</td><td>44</td><td>43</td></tr>
                            <tr><td>XL</td><td>43</td><td>39</td><td>47</td><td>44</td></tr>
                        </tbody>
                    </table>
                </div>
                <p class="fit-note">Sizes can vary slightly by design. Between two sizes? Message us and we will adjust it for you.</p>
            </section>

            <div class="house-cta-wrapper reveal reveal-fade-up">
                <a href="{{ route('design') }}" class="house-primary-cta-btn">
                    SEND YOUR MEASUREMENTS
                </a>
            </div>
        </main>
    </div>

    @include('partials.footer')
@endsection
