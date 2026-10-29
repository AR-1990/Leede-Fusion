@extends('layouts.admin')

@section('title', 'Design Studio')
@section('page_title', 'Design Studio')
@section('page_subtitle', 'The choices customers see when designing their own outfit')

@section('content')
<div x-data="{ open: @js(old('group') ? true : false) }">
    @if (session('status'))
        <div class="admin-flash">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="admin-action-bar">
        <p>Each group is a step in the <a href="{{ route('design') }}" target="_blank">Design Studio</a>. Prices add to the customer's live estimate. Hidden options stay saved but are not shown.</p>
        <button type="button" class="btn-admin-primary" @click="open = !open" x-text="open ? 'Close form' : '+ ADD OPTION'"></button>
    </div>

    <section class="admin-surface" x-show="open" x-cloak x-transition>
        <h3 class="admin-surface-title">NEW OPTION</h3>
        @include('admin.partials.design-option-form', ['option' => null, 'action' => route('admin.design-options.store'), 'method' => 'POST'])
    </section>

    @foreach ($groups as $key => $label)
        <section class="admin-surface">
            <h3 class="admin-surface-title">{{ strtoupper($label) }} <span class="admin-pill">{{ count($options[$key] ?? []) }}</span></h3>
            @forelse ($options[$key] ?? [] as $option)
                <details class="studio-admin-row">
                    <summary>
                        @if ($option->image)
                            <img src="{{ $option->image }}" alt="">
                        @else
                            <span class="studio-admin-mono">{{ mb_substr($option->label, 0, 1) }}</span>
                        @endif
                        <span class="studio-admin-label">
                            <strong>{{ $option->label }}</strong>
                            <small>{{ $option->description }}</small>
                        </span>
                        <span class="studio-admin-price">{{ $option->price ? 'Rs. '.number_format($option->price) : 'Included' }}</span>
                        @unless ($option->is_active)
                            <span class="admin-pill">Hidden</span>
                        @endunless
                        <span class="edit-btn">EDIT</span>
                    </summary>
                    @include('admin.partials.design-option-form', ['option' => $option, 'action' => route('admin.design-options.update', $option), 'method' => 'PUT'])
                    <form method="POST" action="{{ route('admin.design-options.destroy', $option) }}" onsubmit="return confirm('Delete this option?');" style="margin-top: 8px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">DELETE OPTION</button>
                    </form>
                </details>
            @empty
                <p class="post-hint">No options yet, so this step is hidden from customers.</p>
            @endforelse
        </section>
    @endforeach
</div>
@endsection
