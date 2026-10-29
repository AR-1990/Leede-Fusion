@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Fulfillment, tracking, and customer details')

@section('content')
<div class="orders-portal-wrap"
     @dt-manage-order.window="openManageById($event.detail.id)"
     x-data="{
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
        window.ordersTable = adminDataTable('#orders-table', @js(route('admin.data.orders')), [
            { data: 'reference', name: 'reference' },
            { data: 'tracking_number', name: 'tracking_number' },
            { data: 'customer', name: 'customer' },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'total_amount', name: 'total_amount', searchable: false },
            { data: 'status', name: 'status' },
            { data: 'actions', orderable: false, searchable: false, className: 'dt-actions' },
        ], {
            order: [[3, 'desc']],
            emptyText: 'No orders placed yet.',
        });

        // Support ?manage={id} deep link (from the dashboard).
        const manageId = Number(new URLSearchParams(window.location.search).get('manage'));
        if (manageId) {
            this.openManageById(manageId);
        }
    },

    async openManageById(id) {
        try {
            this.openManage(await adminRecord('order', id));
        } catch (e) {
            alert(e.message);
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

    orderRef(order) {
        return 'ORD-' + String(order.id).padStart(5, '0');
    },

    customerName(order) {
        return order.user?.name || order.customer_name || 'Guest customer';
    },

    customerContact(order) {
        return order.customer_email || order.user?.email || order.customer_phone || order.customer_whatsapp || '';
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

            window.ordersTable?.ajax.reload(null, false);
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

    <!-- Orders Table (server-side) -->
    <div class="admin-table-container admin-dt">
        <table id="orders-table" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>ORDER</th>
                    <th>TRACKING</th>
                    <th>CUSTOMER</th>
                    <th>DATE</th>
                    <th>AMOUNT</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Order Manage Modal -->
    <template x-if="selected">
        <div class="portal-modal-overlay" @click.self="closeManage()" x-cloak>
            <div class="portal-modal-panel portal-modal-panel--wide">
                <div class="portal-modal-head">
                    <div>
                        <h2 class="portal-modal-title" x-text="'Order ' + orderRef(selected)"></h2>
                        <p class="portal-modal-sub" x-text="[customerName(selected), customerContact(selected)].filter(Boolean).join(' · ')"></p>
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
                            <p class="portal-detail-value" style="font-weight: 700;" x-text="customerName(selected)"></p>
                            <p class="portal-detail-value" style="margin-top: 4px;" x-show="selected.customer_email || selected.user?.email" x-text="selected.customer_email || selected.user?.email"></p>
                            <p class="portal-detail-value" style="margin-top: 4px;">
                                Phone: <span x-text="selected.customer_phone || '—'"></span>
                                <template x-if="selected.customer_whatsapp">
                                    <span style="margin-left: 12px; color: #16a34a;">(WhatsApp: <span x-text="selected.customer_whatsapp"></span>)</span>
                                </template>
                            </p>
                            <p class="portal-detail-value" style="margin-top: 10px; white-space: pre-wrap; background: #fff; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--admin-border);" x-text="[selected.shipping_address, selected.city].filter(Boolean).join(', ') || '—'"></p>
                        </div>
                        <template x-if="selected.notes">
                            <div class="portal-detail-block" style="grid-column: 1 / -1;">
                                <p class="portal-detail-label">Customer notes</p>
                                <p class="portal-detail-value" style="white-space: pre-wrap;" x-text="selected.notes"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Line Items List -->
                    <h3 class="admin-surface-title" style="margin-bottom: 12px;">ORDERED ITEMS</h3>
                    <ul class="portal-items-list" style="margin-bottom: 20px;">
                        <template x-for="item in (selected.items || [])" :key="item.id">
                            <li>
                                <span>
                                    <strong x-text="item.product?.name || 'Item'"></strong>
                                    <span style="color: var(--admin-text-muted);" x-text="' × ' + item.quantity"></span>
                                    <template x-if="item.size">
                                        <span class="admin-pill" style="margin-left: 8px;" x-text="'Size ' + item.size"></span>
                                    </template>
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
                                        style="width: 100%; box-sizing: border-box; padding: 12px; margin-top: 6px; border: 1px solid var(--admin-border-strong); border-radius: 10px; font-size: 14px; background: #fff;" 
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
                                       style="width: 100%; box-sizing: border-box; padding: 12px; margin-top: 6px; border: 1px solid var(--admin-border-strong); border-radius: 10px; font-family: ui-monospace, monospace; font-size: 13px;" 
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
