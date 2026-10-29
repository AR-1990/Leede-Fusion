@extends('layouts.admin')

@section('title', $order->code)
@section('page_title', 'Custom Order '.$order->code)
@section('page_subtitle', 'Received '.$order->created_at->format('d M Y, g:i A'))

@php
    $digits = preg_replace('/\D/', '', $order->customer_phone);
    $waNumber = str_starts_with($digits, '0') ? '92'.substr($digits, 1) : $digits;
    $price = $order->quoted_price ?? $order->estimated_price;
    $waText = "Assalam o Alaikum {$order->customer_name}! Update from Leede Fusion on your design {$order->code}: it is now at \"{$order->stageLabel()}\"."
        .($order->quoted_price ? ' Confirmed price: Rs. '.number_format($order->quoted_price).'.' : '')
        .' Track it any time: '.route('track');
@endphp

@section('content')
<div class="post-editor">
    <p><a href="{{ route('admin.custom-orders.index') }}" class="btn-admin-ghost" style="text-decoration: none;">&larr; All custom orders</a></p>

    @if (session('status'))
        <div class="admin-flash">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;">{{ $errors->first() }}</div>
    @endif

    <div class="post-editor-grid">
        <div>
            <section class="admin-surface">
                <h3 class="admin-surface-title">DESIGN</h3>
                <dl class="custom-dl">
                    @if ($order->product)
                        <dt>Based on</dt><dd><a href="{{ route('product.show', $order->product) }}" target="_blank">{{ $order->product->name }}</a> (Rs. {{ number_format($order->product->priceValue()) }})</dd>
                    @endif
                    @forelse ($order->selections ?? [] as $group => $sel)
                        <dt>{{ \App\Models\DesignOption::GROUPS[$group] ?? ucfirst($group) }}</dt>
                        <dd>{{ $sel['label'] }} <span class="post-hint">{{ $sel['price'] ? '+ Rs. '.number_format($sel['price']) : '' }}</span></dd>
                    @empty
                        <dt>Options</dt><dd>None chosen. See notes and photos.</dd>
                    @endforelse
                    <dt>Needed by</dt><dd>{{ $order->needed_by?->format('d M Y') ?? 'Not specified' }}</dd>
                </dl>
                @if ($order->notes)
                    <h4 class="post-label">CUSTOMER NOTES</h4>
                    <p class="custom-notes">{{ $order->notes }}</p>
                @endif
            </section>

            <section class="admin-surface">
                <h3 class="admin-surface-title">SIZE & MEASUREMENTS</h3>
                @if ($order->size_mode === 'standard')
                    <p>Standard size <strong>{{ $order->standard_size }}</strong></p>
                @elseif ($order->size_mode === 'custom')
                    <dl class="custom-dl">
                        @foreach ($fields as $key => $label)
                            @if (isset($order->measurements[$key]))
                                <dt>{{ $label }}</dt><dd>{{ $order->measurements[$key] }}"</dd>
                            @endif
                        @endforeach
                    </dl>
                @else
                    <p>Customer will send measurements on WhatsApp.</p>
                @endif
            </section>

            @if (! empty($order->reference_images))
                <section class="admin-surface">
                    <h3 class="admin-surface-title">REFERENCE PHOTOS</h3>
                    <div class="custom-refs">
                        @foreach ($order->reference_images as $img)
                            <a href="{{ $img }}" target="_blank"><img src="{{ $img }}" alt="Reference"></a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="post-side">
            <section class="admin-surface">
                <h3 class="admin-surface-title">CUSTOMER</h3>
                <p style="margin: 0 0 4px; font-weight: 600;">{{ $order->customer_name }}</p>
                <p class="post-hint">{{ $order->customer_phone }} · {{ $order->city }}</p>
                @if ($order->customer_email)
                    <p class="post-hint"><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></p>
                @endif
                <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode($waText) }}" target="_blank" rel="noopener noreferrer" class="btn-admin-primary" style="display: block; text-align: center; text-decoration: none; margin-top: 12px;">
                    SEND WHATSAPP UPDATE
                </a>
                <p class="post-hint">Opens WhatsApp with the current stage{{ $order->quoted_price ? ' and price' : '' }} filled in.</p>
            </section>

            <section class="admin-surface">
                <h3 class="admin-surface-title">PROGRESS</h3>
                <form method="POST" action="{{ route('admin.custom-orders.update', $order) }}">
                    @csrf
                    @method('PUT')
                    <label class="post-label">STAGE</label>
                    <select name="stage" class="admin-input post-input">
                        @foreach ($stages as $key => $label)
                            <option value="{{ $key }}" @selected($order->stage === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <label class="post-label">CONFIRMED PRICE (RS.)</label>
                    <input type="number" name="quoted_price" min="0" class="admin-input post-input" value="{{ old('quoted_price', $order->quoted_price) }}" placeholder="Estimate: {{ number_format($order->estimated_price) }}">
                    <label class="post-label">NOTE FOR CUSTOMER (shown on tracking page)</label>
                    <textarea name="admin_note" rows="3" class="admin-input post-input" maxlength="2000">{{ old('admin_note', $order->admin_note) }}</textarea>
                    <button type="submit" class="btn-admin-primary" style="width: 100%; margin-top: 12px;">SAVE PROGRESS</button>
                </form>

                <h4 class="post-label">HISTORY</h4>
                <ul class="custom-history">
                    @foreach (array_reverse($order->stage_history ?? []) as $h)
                        <li><strong>{{ $stages[$h['stage']] ?? $h['stage'] }}</strong><span>{{ \Illuminate\Support\Carbon::parse($h['at'])->format('d M, g:i A') }}</span></li>
                    @endforeach
                </ul>
            </section>
        </aside>
    </div>
</div>
@endsection
