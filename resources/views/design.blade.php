@extends('layouts.app')

@section('title', 'Design Studio: Create Your Own Outfit | Leede Fusion Karachi')
@section('description', 'Design your own outfit with Leede Fusion: choose the garment, fabric, neckline, sleeves and embroidery, add your measurements and inspiration photos, and get a live price estimate.')

@php
    $optionStepCount = count($steps);
    // Open the studio on the step that holds the first validation error.
    $errorStep = 0;
    if ($errors->any()) {
        $first = array_key_first($errors->getMessages());
        $errorStep = match (true) {
            str_starts_with($first, 'customer_') || $first === 'city' || $first === 'needed_by' => $optionStepCount + 2,
            str_starts_with($first, 'reference_images') || $first === 'notes' || $first === 'selections' => $optionStepCount + 1,
            str_starts_with($first, 'measurements') || in_array($first, ['size_mode', 'standard_size'], true) => $optionStepCount,
            default => 0,
        };
    }
    $oldSelections = collect(old('selections', []))->map(fn ($v) => (int) $v)->filter();
@endphp

@section('content')
    @include('partials.navbar')

    <div class="studio-page" x-data="designStudio({
        steps: @js($steps),
        base: @js($baseProduct ? ['name' => $baseProduct->name, 'price' => $baseProduct->priceValue()] : null),
        selected: @js($oldSelections),
        step: {{ $errorStep }},
        sizeMode: @js(old('size_mode', 'standard')),
    })">
        <header class="studio-hero">
            <div class="studio-hero-inner reveal reveal-fade-up">
                <span class="house-page-badge">THE DESIGN STUDIO</span>
                <h1 class="studio-title">Your idea. <em>Our craft.</em></h1>
                <p class="house-main-subtitle">
                    Build your outfit step by step and watch the estimate update live. Our atelier confirms the final design and price with you on WhatsApp.
                </p>
            </div>
        </header>

        <form method="POST" action="{{ route('design.store') }}" enctype="multipart/form-data" class="studio-layout" @submit="submitting = true">
            @csrf
            @if ($baseProduct)
                <input type="hidden" name="product_id" value="{{ $baseProduct->id }}">
            @endif

            <div class="studio-main">
                {{-- Step rail --}}
                <ol class="studio-rail" aria-label="Design steps">
                    <template x-for="(label, i) in stepLabels" :key="i">
                        <li>
                            <button type="button" class="studio-rail-step" :class="{ 'is-active': step === i, 'is-done': i < step || isStepDone(i) }" @click="go(i)">
                                <span class="studio-rail-num" x-text="String(i + 1).padStart(2, '0')"></span>
                                <span class="studio-rail-label" x-text="label"></span>
                            </button>
                        </li>
                    </template>
                </ol>

                @if ($errors->any())
                    <div class="studio-errors" role="alert">
                        <strong>Please check a few details:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        @if ($errors->has('reference_images.*') || old('customer_name'))
                            <p>For your security, photos need to be selected again after an error.</p>
                        @endif
                    </div>
                @endif

                @if ($baseProduct)
                    <div class="studio-base">
                        <img src="{{ \App\Support\Catalog::primaryImage($baseProduct) }}" alt="">
                        <div>
                            <span class="studio-base-kicker">CUSTOMIZING</span>
                            <strong>{{ $baseProduct->name }}</strong>
                            <span>Starting from Rs. {{ number_format($baseProduct->priceValue()) }}. Pick only what you want to change.</span>
                        </div>
                        <a href="{{ route('design') }}" class="studio-base-remove">Start from scratch</a>
                    </div>
                @endif

                {{-- Option steps (managed in Admin → Design Studio) --}}
                @foreach ($steps as $i => $step)
                    <section class="studio-panel" x-show="step === {{ $i }}" @if ($i !== $errorStep) x-cloak @endif>
                        <div class="studio-panel-head">
                            <span class="studio-panel-kicker">STEP {{ $i + 1 }}</span>
                            <h2>Choose your {{ strtolower($step['label']) }}</h2>
                            <p>Optional. Skip any step and tell us in your notes instead.</p>
                        </div>
                        <div class="studio-options">
                            @foreach ($step['options'] as $opt)
                                <label class="studio-option" :class="{ 'is-selected': selected['{{ $step['key'] }}'] === {{ $opt['id'] }} }">
                                    <input type="radio" name="selections[{{ $step['key'] }}]" value="{{ $opt['id'] }}"
                                           @checked($oldSelections->get($step['key']) === $opt['id'])
                                           @click="toggle('{{ $step['key'] }}', {{ $opt['id'] }}, $event)">
                                    <span class="studio-option-media">
                                        @if ($opt['image'])
                                            <img src="{{ $opt['image'] }}" alt="" loading="lazy">
                                        @else
                                            <span class="studio-option-monogram">{{ mb_substr($opt['label'], 0, 1) }}</span>
                                        @endif
                                    </span>
                                    <span class="studio-option-body">
                                        <span class="studio-option-label">{{ $opt['label'] }}</span>
                                        @if ($opt['description'])
                                            <span class="studio-option-desc">{{ $opt['description'] }}</span>
                                        @endif
                                        <span class="studio-option-price">{{ $opt['price'] > 0 ? '+ Rs. '.number_format($opt['price']) : 'Included' }}</span>
                                    </span>
                                    <span class="studio-option-check" aria-hidden="true">✓</span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach

                {{-- Size & fit --}}
                <section class="studio-panel" x-show="step === {{ $optionStepCount }}" @if ($errorStep !== $optionStepCount) x-cloak @endif>
                    <div class="studio-panel-head">
                        <span class="studio-panel-kicker">STEP {{ $optionStepCount + 1 }}</span>
                        <h2>Size & fit</h2>
                        <p>Pick a standard size, enter your measurements, or send them later. Need help? See the <a href="{{ route('the-fit') }}" target="_blank">measurement guide</a>.</p>
                    </div>

                    <div class="studio-segment" role="radiogroup">
                        @foreach (['standard' => 'Standard size', 'custom' => 'My measurements', 'later' => 'Send later on WhatsApp'] as $mode => $label)
                            <label class="studio-segment-item" :class="{ 'is-active': sizeMode === '{{ $mode }}' }">
                                <input type="radio" name="size_mode" value="{{ $mode }}" x-model="sizeMode" @checked(old('size_mode', 'standard') === $mode)>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div x-show="sizeMode === 'standard'" class="size-chips studio-sizes">
                        @foreach ($sizes as $s)
                            <label class="size-chip">
                                <input type="radio" name="standard_size" value="{{ $s }}" @checked(old('standard_size') === $s)>
                                <span>{{ $s }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div x-show="sizeMode === 'custom'" x-cloak class="studio-measure-grid">
                        @foreach ($measurementFields as $key => $label)
                            <label class="studio-field">
                                <span>{{ $label }} <small>(inches)</small></span>
                                <input type="number" step="0.25" min="1" max="120" name="measurements[{{ $key }}]" value="{{ old('measurements.'.$key) }}" inputmode="decimal">
                            </label>
                        @endforeach
                    </div>

                    <p x-show="sizeMode === 'later'" x-cloak class="studio-note">
                        No problem. After you submit, we will message you on WhatsApp to take your measurements.
                    </p>
                </section>

                {{-- Inspiration --}}
                <section class="studio-panel" x-show="step === {{ $optionStepCount + 1 }}" @if ($errorStep !== $optionStepCount + 1) x-cloak @endif>
                    <div class="studio-panel-head">
                        <span class="studio-panel-kicker">STEP {{ $optionStepCount + 2 }}</span>
                        <h2>Your inspiration</h2>
                        <p>Upload up to 4 photos: a Pinterest look, a sketch, or a dress you love. Then describe your idea in your own words.</p>
                    </div>

                    <label class="studio-dropzone" :class="{ 'is-dragging': dragging }"
                           @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                           @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)">
                        <input type="file" name="reference_images[]" accept="image/jpeg,image/png,image/webp" multiple x-ref="files" @change="addFiles($event.target.files)">
                        <span class="studio-dropzone-icon" aria-hidden="true">✦</span>
                        <strong>Drop photos here or tap to choose</strong>
                        <span>JPG, PNG or WebP · up to 8 MB each · max 4</span>
                    </label>
                    <p class="studio-file-error" x-show="fileError" x-text="fileError" x-cloak></p>

                    <div class="studio-previews" x-show="previews.length" x-cloak>
                        <template x-for="(src, i) in previews" :key="src">
                            <figure>
                                <img :src="src" alt="Reference photo preview">
                                <button type="button" @click="removeFile(i)" aria-label="Remove photo">×</button>
                            </figure>
                        </template>
                    </div>

                    <label class="studio-field studio-field-wide">
                        <span>Describe your idea</span>
                        <textarea name="notes" rows="5" maxlength="2000" placeholder="e.g. Maroon chiffon frock for my sister's mehndi, gold dabka on the neckline, slightly flared…">{{ old('notes') }}</textarea>
                    </label>
                </section>

                {{-- Details --}}
                <section class="studio-panel" x-show="step === {{ $optionStepCount + 2 }}" @if ($errorStep !== $optionStepCount + 2) x-cloak @endif>
                    <div class="studio-panel-head">
                        <span class="studio-panel-kicker">STEP {{ $optionStepCount + 3 }}</span>
                        <h2>Your details</h2>
                        <p>We will confirm your design and price on WhatsApp before any work begins.</p>
                    </div>
                    <div class="studio-details-grid">
                        <label class="studio-field">
                            <span>Full name *</span>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required maxlength="120" autocomplete="name">
                        </label>
                        <label class="studio-field">
                            <span>WhatsApp number *</span>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required maxlength="32" placeholder="03XX XXXXXXX" autocomplete="tel">
                        </label>
                        <label class="studio-field">
                            <span>City *</span>
                            <input type="text" name="city" value="{{ old('city') }}" required maxlength="100" autocomplete="address-level2">
                        </label>
                        <label class="studio-field">
                            <span>Email (optional)</span>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" maxlength="255" autocomplete="email">
                        </label>
                        <label class="studio-field">
                            <span>Needed by (optional)</span>
                            <input type="date" name="needed_by" value="{{ old('needed_by') }}" min="{{ now()->addDay()->toDateString() }}">
                        </label>
                    </div>
                </section>

                <div class="studio-nav">
                    <button type="button" class="studio-btn-ghost" x-show="step > 0" @click="go(step - 1)">&larr; Back</button>
                    <span></span>
                    <button type="button" class="studio-btn" x-show="step < lastStep" @click="go(step + 1)">Continue &rarr;</button>
                    <button type="submit" class="studio-btn studio-btn-gold" x-show="step === lastStep" x-cloak :disabled="submitting"
                            x-text="submitting ? 'Sending to the atelier…' : 'Send my design'">Send my design</button>
                </div>
            </div>

            {{-- Live summary --}}
            <aside class="studio-summary" aria-live="polite">
                <span class="studio-summary-kicker">YOUR DESIGN</span>
                <ul class="studio-summary-list">
                    <template x-if="base">
                        <li><span x-text="base.name"></span><span x-text="money(base.price)"></span></li>
                    </template>
                    <template x-for="item in chosen" :key="item.group">
                        <li>
                            <span><small x-text="item.groupLabel"></small> <span x-text="item.label"></span></span>
                            <span x-text="item.price ? '+ ' + money(item.price) : 'Included'"></span>
                        </li>
                    </template>
                    <li x-show="!base && !chosen.length" class="studio-summary-empty">Start choosing, and your design will appear here.</li>
                </ul>
                <div class="studio-summary-total">
                    <span>Estimate</span>
                    <strong x-text="money(total)">Rs. 0</strong>
                </div>
                <p class="studio-summary-note">An estimate only. Your final price is confirmed by the atelier before stitching starts. Nothing is charged now.</p>
                <a href="{{ route('track') }}" class="studio-summary-link">Already ordered? Track it &rarr;</a>
            </aside>
        </form>
    </div>

    @include('partials.footer')
@endsection

@push('scripts')
<script>
    window.designStudio = function (config) {
        return {
            steps: config.steps,
            base: config.base,
            selected: Object.fromEntries(Object.entries(config.selected || {}).map(([k, v]) => [k, Number(v)])),
            step: config.step || 0,
            sizeMode: config.sizeMode || 'standard',
            submitting: false,
            dragging: false,
            previews: [],
            fileError: '',
            get stepLabels() {
                return [...this.steps.map((s) => s.label), 'Size & Fit', 'Inspiration', 'Your Details'];
            },
            get lastStep() {
                return this.stepLabels.length - 1;
            },
            get chosen() {
                return this.steps
                    .filter((s) => this.selected[s.key])
                    .map((s) => {
                        const opt = s.options.find((o) => o.id === this.selected[s.key]);
                        return opt ? { group: s.key, groupLabel: s.label, label: opt.label, price: opt.price } : null;
                    })
                    .filter(Boolean);
            },
            get total() {
                return (this.base ? this.base.price : 0) + this.chosen.reduce((sum, c) => sum + c.price, 0);
            },
            money(n) {
                return 'Rs. ' + Number(n || 0).toLocaleString('en-PK');
            },
            isStepDone(i) {
                return i < this.steps.length && !!this.selected[this.steps[i].key];
            },
            go(i) {
                this.step = Math.max(0, Math.min(this.lastStep, i));
                const top = this.$root.querySelector('.studio-rail');
                if (top) top.scrollIntoView({ behavior: 'smooth', block: 'start' });
            },
            // Clicking a chosen card again clears it, so every step stays optional.
            toggle(group, id, event) {
                if (this.selected[group] === id) {
                    event.target.checked = false;
                    delete this.selected[group];
                    this.selected = { ...this.selected };
                } else {
                    this.selected = { ...this.selected, [group]: id };
                }
            },
            addFiles(fileList) {
                this.fileError = '';
                const input = this.$refs.files;
                const dt = new DataTransfer();
                [...(input.files || [])].forEach((f) => dt.items.add(f));
                if (fileList !== input.files) {
                    [...fileList].forEach((f) => dt.items.add(f));
                }
                const files = [...dt.files].filter((f) => {
                    if (!['image/jpeg', 'image/png', 'image/webp'].includes(f.type)) {
                        this.fileError = 'Only JPG, PNG or WebP photos can be added.';
                        return false;
                    }
                    if (f.size > 8 * 1024 * 1024) {
                        this.fileError = `${f.name} is larger than 8 MB.`;
                        return false;
                    }
                    return true;
                });
                if (files.length > 4) {
                    this.fileError = 'You can add up to 4 photos; the first 4 were kept.';
                }
                const keep = new DataTransfer();
                files.slice(0, 4).forEach((f) => keep.items.add(f));
                input.files = keep.files;
                this.refreshPreviews();
            },
            removeFile(index) {
                const input = this.$refs.files;
                const keep = new DataTransfer();
                [...input.files].forEach((f, i) => i !== index && keep.items.add(f));
                input.files = keep.files;
                this.refreshPreviews();
            },
            refreshPreviews() {
                this.previews.forEach((url) => URL.revokeObjectURL(url));
                this.previews = [...this.$refs.files.files].map((f) => URL.createObjectURL(f));
            },
        };
    };
</script>
@endpush
