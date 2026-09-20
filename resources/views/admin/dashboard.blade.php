@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Revenue, orders, and live store activity')

@section('content')
<div class="dashboard-view">
    <!-- 4 Stat Cards: Natural Case, Normal Weights, Clean Human Styling -->
    <div class="stats-grid">
        @foreach($stats as $index => $stat)
            <div class="stat-card">
                <div class="stat-card-top">
                    <span class="stat-label">
                        @if($stat['label'] === 'TOTAL REVENUE')
                            Total revenue
                        @elseif($stat['label'] === 'TOTAL ORDERS')
                            Total orders
                        @elseif($stat['label'] === 'TOTAL CUSTOMERS')
                            Customers
                        @elseif($stat['label'] === 'AVG ORDER VALUE')
                            Average order value
                        @else
                            {{ ucwords(strtolower($stat['label'])) }}
                        @endif
                    </span>
                    <div class="stat-icon-box">
                        @if($index === 0)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        @elseif($index === 1)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        @elseif($index === 2)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        @else
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                        @endif
                    </div>
                </div>
                <div class="stat-value">{{ $stat['value'] }}</div>
                <div class="stat-sub">
                    @if($index === 0)
                        All-time gross sales
                    @elseif($index === 1)
                        Orders placed across all channels
                    @elseif($index === 2)
                        Registered customer accounts
                    @else
                        Calculated average checkout
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Live Orders Section -->
    <div class="admin-surface">
        <div class="admin-action-bar">
            <div>
                <h2 class="section-title-small">Recent orders</h2>
                <p class="section-subtitle-small">Live transactions from your storefront.</p>
            </div>
            <a href="{{ route('admin.orders') }}" class="btn-table-action">
                <span>View all orders</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <span style="font-family: ui-monospace, SFMono-Regular, monospace; font-size: 12px; font-weight: 600; color: #0f172a;">
                                    #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: #0f172a;">
                                    {{ $order->user->name ?? $order->customer_name ?? 'Guest customer' }}
                                </div>
                                <div style="font-size: 12px; color: #64748b;">
                                    {{ $order->user->email ?? $order->customer_email ?? 'Direct checkout' }}
                                </div>
                            </td>
                            <td style="color: #64748b; font-size: 13px;">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td style="font-weight: 500; color: #0f172a;">
                                Rs. {{ number_format((float) $order->total_amount) }}
                            </td>
                            <td>
                                <span class="status-badge {{ $order->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.orders') }}?manage={{ $order->id }}" class="btn-table-action">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px; color: #64748b; font-size: 13px;">
                                No customer orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
