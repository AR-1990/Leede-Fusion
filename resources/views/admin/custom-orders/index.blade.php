@extends('layouts.admin')

@section('title', 'Custom Orders')
@section('page_title', 'Custom Orders')
@section('page_subtitle', 'Designs submitted through the Design Studio')

@section('content')
<div x-data="{
    stage: @js($stage ?? ''),
    init() {
        window.customOrdersTable = adminDataTable('#custom-orders-table', @js(route('admin.data.custom-orders')), [
            { data: 'code', name: 'code' },
            { data: 'customer', name: 'customer' },
            { data: 'design', orderable: false, searchable: false },
            { data: 'price', name: 'price', searchable: false },
            { data: 'stage', name: 'stage', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'actions', orderable: false, searchable: false, className: 'dt-actions' },
        ], {
            order: [[5, 'desc']],
            params: () => ({ stage: this.stage }),
            emptyText: 'No custom orders yet. Share the Design Studio with your customers.',
        });
    },
    setStage(stage) {
        this.stage = stage;
        const url = new URL(window.location.href);
        stage ? url.searchParams.set('stage', stage) : url.searchParams.delete('stage');
        history.replaceState(null, '', url);
        window.customOrdersTable?.ajax.reload();
    }
}">
    <nav class="custom-stage-filter">
        <a href="{{ route('admin.custom-orders.index') }}" :class="{ 'is-active': !stage }" @click.prevent="setStage('')">All <span>{{ $counts->sum() }}</span></a>
        @foreach ($stages as $key => $label)
            <a href="{{ route('admin.custom-orders.index', ['stage' => $key]) }}" :class="{ 'is-active': stage === @js($key) }" @click.prevent="setStage(@js($key))">
                {{ $label }} <span>{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </nav>

    <div class="admin-table-container admin-dt">
        <table id="custom-orders-table" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>REFERENCE</th>
                    <th>CUSTOMER</th>
                    <th>DESIGN</th>
                    <th>ESTIMATE</th>
                    <th>STAGE</th>
                    <th>RECEIVED</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>
    </div>
    <p class="post-hint">* estimate, not yet quoted.</p>
</div>
@endsection
