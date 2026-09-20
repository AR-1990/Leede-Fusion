@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Fulfillment, tracking, and customer details')

@section('content')
<div class="orders-portal-wrap" x-data="{
    orders: {{ Js::from($orders) }},
    selected: null,
    editStatus: 'pending',
    editTracking: '',
    saving: false,
    errorMessage: '',

    statuses: [
        { value: 'pending', label: 'Order placed' },
        { value: 'processing', label: 'Preparing your order' },
        { value: 'shipped', label: 'Shipped' },
        { value: 'out_for_delivery', label: 'Out for delivery' },
        { value: 'delivered', label: 'Delivered' },
        { value: 'completed', label: 'Completed' },
        { value: 'cancelled', label: 'Cancelled' }
    ],

    init() {
        // Support ?manage={id} deep link
        const params = new URLSearchParams(window.location.search);
        const manageId = params.get('manage');
        if (manageId) {
            const ord = this.orders.find(o => o.id == manageId);
            if (ord) {
                this.openManage(ord);
            }
        }
    },

    getAuthHeaders() {
        const token = $store.auth.token || localStorage.getItem('wahla_token') || '';
        return {
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : '',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        };
    },

    getStatusLabel(val) {
        const found = this.statuses.find(s => s.value === val);
        return found ? found.label : (val ? val.replace('_', ' ') : 'Pending');
    },

    openManage(order) {
        this.selected = JSON.parse(JSON.stringify(order));
        this.editStatus = this.selected.status || 'pending';
        this.editTracking = this.selected.tracking_number || '';
        this.errorMessage = '';
    },

    closeManage() {
        this.selected = null;
        this.errorMessage = '';
    },

    async saveOrder() {
        if (!this.selected) return;
        this.saving = true;
        this.errorMessage = '';

        const payload = {
            status: this.editStatus,
            tracking_number: this.editTracking.trim() || null
        };

        try {
            const res = await fetch(`/api/admin/orders/${this.selected.id}`, {
                method: 'PATCH',
                headers: {
                    ...this.getAuthHeaders(),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!res.ok) {
                if (data.errors) {
                    this.errorMessage = Object.values(data.errors).flat().join(' • ');
                } else {
                    this.errorMessage = data.message || 'Failed to update order.';
                }
                this.saving = false;
                return;
            }

            const idx = this.orders.findIndex(o => o.id === this.selected.id);
            if (idx !== -1) {
                this.orders[idx] = data;
            }
            this.closeManage();
        } catch (err) {
            this.errorMessage = 'Network error while updating order.';
        } finally {
            this.saving = false;
        }
    }
}">

    <div class="admin-action-bar" style="margin-bottom: 20px;">
        <p>Update status and tracking. Customers can monitor live updates in their dashboard.</p>
    </div>

    <!-- Orders Table -->
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ORDER</th>
                    <th>TRACKING</th>
                    <th>CUSTOMER</th>
                    <th>DATE</th>
                    <th>AMOUNT</th>
                    <th>STATUS</th>
                    <th style="text-align: right;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="order in orders" :key="order.id">
                    <tr>
                        <td style="font-weight: 700;" x-text="'#ORD-' + order.id"></td>
                        <td>
                            <code style="font-family: ui-monospace, monospace; font-size: 12px; background: #f8fafc; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0;" x-text="order.tracking_number || '—'"></code>
                        </td>
                        <td>
                            <div style="font-weight: 600;" x-text="order.user?.name || order.customer_name || 'Guest'"></div>
                            <div style="font-size: 11px; color: #94a3b8;" x-text="order.customer_email || order.user?.email || ''"></div>
                        </td>
                        <td style="color: #64748b; font-size: 13px;" x-text="new Date(order.created_at).toLocaleDateString()"></td>
                        <td style="font-weight: 700;" x-text="'Rs. ' + Number(order.total_amount).toLocaleString()"></td>
                        <td>
                            <span class="status-badge" :class="order.status" x-text="getStatusLabel(order.status)"></span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn-table-action" @click="openManage(order)">
                                Manage
                            </button>
                        </td>
                    </tr>
                </template>
                <template x-if="orders.length === 0">
                    <tr>
                        <td colSpan="7" style="text-align: center; padding: 48px; color: #94a3b8;">
                            No orders placed yet.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Order Manage Modal -->
    <template x-if="selected">
        <div class="portal-modal-overlay" @click.self="closeManage()" x-cloak>
            <div class="portal-modal-panel portal-modal-panel--wide">
                <div class="portal-modal-head">
                    <div>
                        <h2 class="portal-modal-title" x-text="'ORDER #ORD-' + selected.id"></h2>
                        <p class="portal-modal-sub" x-text="(selected.user?.name || selected.customer_name || 'Customer') + ' · ' + (selected.user?.email || selected.customer_email || '')"></p>
                    </div>
                    <button type="button" class="portal-modal-close" aria-label="Close" @click="closeManage()">
                        &times;
                    </button>
                </div>

                <div class="portal-modal-body">
                    <div x-show="errorMessage" class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;" x-text="errorMessage"></div>

                    <!-- Shipping Address Info -->
                    <div class="portal-detail-grid" style="margin-bottom: 20px;">
                        <div class="portal-detail-block" style="grid-column: 1 / -1;">
                            <p class="portal-detail-label">Ship to</p>
                            <p class="portal-detail-value" style="font-weight: 700;" x-text="selected.customer_name"></p>
                            <p class="portal-detail-value" style="margin-top: 4px;" x-text="selected.customer_email"></p>
                            <p class="portal-detail-value" style="margin-top: 4px;">
                                Phone: <span x-text="selected.customer_phone || '—'"></span>
                                <template x-if="selected.customer_whatsapp">
                                    <span style="margin-left: 12px; color: #16a34a;">(WhatsApp: <span x-text="selected.customer_whatsapp"></span>)</span>
                                </template>
                            </p>
                            <p class="portal-detail-value" style="margin-top: 10px; white-space: pre-wrap; background: #fff; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;" x-text="selected.shipping_address || '—'"></p>
                        </div>
                    </div>

                    <!-- Line Items List -->
                    <h3 class="admin-surface-title" style="margin-bottom: 12px;">ORDERED ITEMS</h3>
                    <ul class="portal-items-list" style="margin-bottom: 20px;">
                        <template x-for="item in (selected.items || [])" :key="item.id">
                            <li>
                                <span>
                                    <strong x-text="item.product?.name || 'Item'"></strong>
                                    <span style="color: #64748b;" x-text="' × ' + item.quantity"></span>
                                </span>
                                <span style="font-weight: 700;" x-text="'Rs. ' + Number(item.price).toLocaleString()"></span>
                            </li>
                        </template>
                    </ul>

                    <!-- Fulfillment & Status Controls -->
                    <form @submit.prevent="saveOrder()">
                        <div class="portal-detail-grid" style="margin-bottom: 16px;">
                            <div class="portal-detail-block" style="grid-column: 1 / -1;">
                                <label class="portal-detail-label" for="admin-order-status">ORDER STATUS</label>
                                <select id="admin-order-status" 
                                        class="admin-input" 
                                        style="width: 100%; box-sizing: border-box; padding: 12px; margin-top: 6px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; background: #fff;" 
                                        x-model="editStatus">
                                    <template x-for="s in statuses" :key="s.value">
                                        <option :value="s.value" x-text="s.label"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="portal-detail-block" style="grid-column: 1 / -1;">
                                <label class="portal-detail-label" for="admin-order-tracking">COURIER TRACKING REFERENCE</label>
                                <input id="admin-order-tracking" 
                                       type="text" 
                                       class="admin-input" 
                                       style="width: 100%; box-sizing: border-box; padding: 12px; margin-top: 6px; border: 1px solid #cbd5e1; border-radius: 10px; font-family: ui-monospace, monospace; font-size: 13px;" 
                                       x-model="editTracking" 
                                       placeholder="e.g. WCH-00000001 or TCS-89218273">
                            </div>
                        </div>

                        <div class="portal-modal-footer" style="border: none; padding-top: 0; margin-top: 16px; justify-content: flex-end;">
                            <button type="button" class="btn-admin-secondary" @click="closeManage()" style="margin-right: 10px;">
                                Close
                            </button>
                            <button type="submit" class="btn-portal-primary" :disabled="saving" x-text="saving ? 'SAVING CHANGES...' : 'SAVE CHANGES'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
