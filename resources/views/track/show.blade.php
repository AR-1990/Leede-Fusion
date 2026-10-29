@extends('layouts.app')

@section('title', $code.' · Order Status | Leede Fusion')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@php
    $keys = array_keys($stages);
    $currentIndex = array_search($current, $keys, true);
    $currentIndex = $currentIndex === false ? 0 : $currentIndex;
    $whatsappText = "Hi Leede Fusion! I'd like an update on my order {$code}.";
@endphp

@section('content')
    @include('partials.navbar')

    <main class="track-page">
        <section class="track-card track-card-wide reveal reveal-fade-up">
            @if (session('just_created'))
                <div class="track-celebrate">
                    <span class="track-celebrate-icon" aria-hidden="true">✦</span>
                    <div>
                        <strong>Your design is with our atelier!</strong>
                        <p>We will message you on WhatsApp shortly to confirm the details and final price. Save your reference below.</p>
                    </div>
                </div>
            @endif

            <div class="track-head">
                <div>
                    <span class="house-page-badge">{{ $isCustom ? 'CUSTOM DESIGN' : 'SHOP ORDER' }}</span>
                    <h1 class="track-code">{{ $code }}</h1>
                    <p class="track-meta">Placed {{ $record->created_at->format('d M Y, g:i A') }} · {{ $record->customer_name }}</p>
                </div>
                <div class="track-status-pill {{ $cancelled ? 'is-cancelled' : '' }}">
                    {{ $cancelled ? 'Cancelled' : ($stages[$current] ?? ucfirst(str_replace('_', ' ', $current))) }}
                </div>
            </div>

            @unless ($cancelled)
                <ol class="track-timeline">
                    @foreach ($stages as $key => $label)
                        @php($state = $loop->index < $currentIndex ? 'done' : ($loop->index === $currentIndex ? 'current' : 'todo'))
                        <li class="track-step is-{{ $state }}">
                            <span class="track-dot" aria-hidden="true">{{ $state === 'done' ? '✓' : $loop->iteration }}</span>
                            <div>
                                <strong>{{ $label }}</strong>
                                @if (! empty($times[$key]))
                                    <span>{{ \Illuminate\Support\Carbon::parse($times[$key])->format('d M, g:i A') }}</span>
                                @elseif ($state === 'current')
                                    <span>In progress</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endunless

            <div class="track-details">
                @if ($isCustom)
                    <div class="track-box">
                        <h2>Your design</h2>
                        <ul class="track-list">
                            @if ($record->product)
                                <li><span>Based on</span><a href="{{ route('product.show', $record->product) }}">{{ $record->product->name }}</a></li>
                            @endif
                            @foreach ($record->selections ?? [] as $group => $sel)
                                <li><span>{{ \App\Models\DesignOption::GROUPS[$group] ?? ucfirst($group) }}</span><strong>{{ $sel['label'] }}</strong></li>
                            @endforeach
                            <li><span>Size</span><strong>
                                @if ($record->size_mode === 'standard') {{ $record->standard_size }}
                                @elseif ($record->size_mode === 'custom') Custom measurements
                                @else To be shared on WhatsApp
                                @endif
                            </strong></li>
                            @if ($record->needed_by)
                                <li><span>Needed by</span><strong>{{ $record->needed_by->format('d M Y') }}</strong></li>
                            @endif
                        </ul>
                        @if (! empty($record->reference_images))
                            <div class="track-refs">
                                @foreach ($record->reference_images as $img)
                                    <img src="{{ $img }}" alt="Reference photo" loading="lazy">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="track-box">
                        <h2>Price</h2>
                        @if ($record->quoted_price)
                            <p class="track-price">Rs. {{ number_format($record->quoted_price) }}</p>
                            <p class="track-small">Confirmed by the atelier.</p>
                        @else
                            <p class="track-price">Rs. {{ number_format($record->estimated_price) }}</p>
                            <p class="track-small">Estimate. The atelier will confirm your final price.</p>
                        @endif
                        @if ($record->admin_note)
                            <div class="track-note"><strong>Note from the atelier</strong><p>{{ $record->admin_note }}</p></div>
                        @endif
                    </div>
                @else
                    <div class="track-box">
                        <h2>Items</h2>
                        <ul class="track-list">
                            @foreach ($record->items as $item)
                                <li>
                                    <span>{{ $item->product?->name ?? 'Item' }}{{ $item->size ? ' · '.$item->size : '' }} × {{ $item->quantity }}</span>
                                    <strong>Rs. {{ number_format($item->price * $item->quantity) }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="track-box">
                        <h2>Total</h2>
                        <p class="track-price">Rs. {{ number_format((float) $record->total_amount) }}</p>
                        <p class="track-small">Cash on delivery · {{ $record->city }}</p>
                        @if ($record->tracking_number)
                            <div class="track-note"><strong>Courier tracking</strong><p>{{ $record->tracking_number }}</p></div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="track-actions">
                <a href="{{ config('content.project.whatsapp_link') }}?text={{ rawurlencode($whatsappText) }}" target="_blank" rel="noopener noreferrer" class="pdp-btn-whatsapp">Ask for an update on WhatsApp</a>
                <a href="{{ route('collections') }}" class="studio-btn-ghost">Continue shopping</a>
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
