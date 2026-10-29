@extends('layouts.admin')

@section('title', 'Customer Inquiries')
@section('page_title', 'Customer Inquiries')
@section('page_subtitle', 'Review, manage, and respond to incoming contact messages')

@section('content')
<div class="inquiries-portal-wrap"
     @dt-view-inquiry.window="openDetailById($event.detail.id)"
     @dt-delete-inquiry.window="deleteInquiry($event.detail.id)"
     x-data="{
    stats: {{ Js::from($stats) }},
    activeFilter: 'all',
    selected: null,
    editStatus: 'unread',
    adminNotes: '',
    saving: false,
    errorMessage: '',
    successMessage: '',

    init() {
        window.inquiriesTable = adminDataTable('#inquiries-table', @js(route('admin.data.inquiries')), [
            { data: 'id', name: 'id', render: (id) => '#' + id },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'subject', name: 'subject' },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'status', name: 'status', searchable: false },
            { data: 'actions', orderable: false, searchable: false, className: 'dt-actions' },
        ], {
            emptyText: 'No customer inquiries yet.',
            params: () => ({ status: this.activeFilter === 'all' ? '' : this.activeFilter }),
            onData: (json) => { if (json.stats) this.stats = json.stats; },
        });

        const inquiryId = Number(new URLSearchParams(window.location.search).get('view'));
        if (inquiryId) this.openDetailById(inquiryId);
    },

    reloadTable() {
        window.inquiriesTable?.ajax.reload(null, false);
    },

    setFilter(filter) {
        this.activeFilter = filter;
        window.inquiriesTable?.ajax.reload();
    },

    getAuthHeaders() {
        const token = $store.auth.token || localStorage.getItem('wahla_token') || '';
        return {
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : '',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        };
    },

    async openDetailById(id) {
        try {
            this.openDetail(await adminRecord('inquiry', id));
        } catch (e) {
            alert(e.message);
        }
    },

    openDetail(item) {
        this.selected = JSON.parse(JSON.stringify(item));
        this.editStatus = this.selected.status || 'unread';
        this.adminNotes = this.selected.admin_notes || '';
        this.errorMessage = '';
        this.successMessage = '';

        // If currently unread, automatically mark as read upon opening
        if (this.selected.status === 'unread') {
            this.updateStatusDirect(this.selected.id, 'read');
        }
    },

    closeDetail() {
        this.selected = null;
        this.errorMessage = '';
        this.successMessage = '';
    },

    async updateStatusDirect(id, newStatus) {
        try {
            const res = await fetch(`/api/admin/inquiries/${id}/status`, {
                method: 'PATCH',
                headers: {
                    ...this.getAuthHeaders(),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            if (res.ok) {
                this.reloadTable();
                if (this.selected && this.selected.id === id) {
                    this.selected.status = newStatus;
                    this.editStatus = newStatus;
                }
            }
        } catch (e) {}
    },

    async saveChanges() {
        if (!this.selected) return;
        this.saving = true;
        this.errorMessage = '';
        this.successMessage = '';

        try {
            const res = await fetch(`/api/admin/inquiries/${this.selected.id}/status`, {
                method: 'PATCH',
                headers: {
                    ...this.getAuthHeaders(),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: this.editStatus,
                    admin_notes: this.adminNotes.trim() || null
                })
            });

            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                this.errorMessage = data.message || 'Failed to update inquiry.';
                return;
            }

            this.selected.status = this.editStatus;
            this.reloadTable();
            this.successMessage = 'Changes saved successfully.';
            setTimeout(() => { this.successMessage = ''; }, 3000);
        } catch (err) {
            this.errorMessage = 'Network error while saving changes.';
        } finally {
            this.saving = false;
        }
    },

    async deleteInquiry(id) {
        if (!confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')) {
            return;
        }

        try {
            const res = await fetch(`/api/admin/inquiries/${id}`, {
                method: 'DELETE',
                headers: this.getAuthHeaders()
            });

            if (res.ok) {
                this.reloadTable();
                if (this.selected && this.selected.id === id) {
                    this.closeDetail();
                }
            } else {
                alert('Could not delete inquiry. Please try again.');
            }
        } catch (e) {
            alert('Network error while deleting.');
        }
    },

    getCleanPhone(phone) {
        if (!phone) return '';
        const digits = phone.replace(/[^0-9]/g, '');
        return digits.startsWith('0') ? '92' + digits.slice(1) : digits;
    }
}">

    <!-- Stats Overview Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid var(--admin-border); background: #fff;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 6px;">Total Inquiries</p>
            <h3 style="font-size: 26px; font-weight: 800; color: var(--admin-ink); margin: 0;" x-text="stats.total"></h3>
        </div>

        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid #fed7aa; background: #fffaf5;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #ea580c; text-transform: uppercase; margin-bottom: 6px;">Unread Messages</p>
            <h3 style="font-size: 26px; font-weight: 800; color: #c2410c; margin: 0;" x-text="stats.unread"></h3>
        </div>

        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid #bbf7d0; background: #f0fdf4;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #16a34a; text-transform: uppercase; margin-bottom: 6px;">Responded</p>
            <h3 style="font-size: 26px; font-weight: 800; color: #15803d; margin: 0;" x-text="stats.responded"></h3>
        </div>

        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid var(--admin-border); background: #fff;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 6px;">Today's Messages</p>
            <h3 style="font-size: 26px; font-weight: 800; color: var(--admin-ink); margin: 0;" x-text="stats.today"></h3>
        </div>
    </div>

    <!-- Status filter (reloads the server-side table) -->
    <nav class="custom-stage-filter">
        <a href="#" :class="{ 'is-active': activeFilter === 'all' }" @click.prevent="setFilter('all')">All <span x-text="stats.total"></span></a>
        <a href="#" :class="{ 'is-active': activeFilter === 'unread' }" @click.prevent="setFilter('unread')">Unread <span x-text="stats.unread"></span></a>
        <a href="#" :class="{ 'is-active': activeFilter === 'read' }" @click.prevent="setFilter('read')">Read <span x-text="stats.read"></span></a>
        <a href="#" :class="{ 'is-active': activeFilter === 'responded' }" @click.prevent="setFilter('responded')">Responded <span x-text="stats.responded"></span></a>
    </nav>

    <!-- Inquiries Table (server-side) -->
    <div class="admin-table-container admin-dt">
        <table id="inquiries-table" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CUSTOMER</th>
                    <th>CONTACT</th>
                    <th>SUBJECT &amp; PREVIEW</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Message Detail Modal -->
    <template x-if="selected">
        <div class="portal-modal-overlay" @click.self="closeDetail()" x-cloak>
            <div class="portal-modal-panel portal-modal-panel--wide" style="max-width: 680px;">
                <div class="portal-modal-head">
                    <div>
                        <h2 class="portal-modal-title" x-text="'INQUIRY #' + selected.id"></h2>
                        <p class="portal-modal-sub" x-text="selected.name + ' · ' + selected.email"></p>
                    </div>
                    <button type="button" class="portal-modal-close" aria-label="Close" @click="closeDetail()">
                        &times;
                    </button>
                </div>

                <div class="portal-modal-body">
                    <div x-show="errorMessage" class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;" x-text="errorMessage"></div>
                    <div x-show="successMessage" style="margin-bottom: 16px; font-weight: 600; padding: 10px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 6px;" x-text="successMessage"></div>

                    <!-- Sender Info Grid -->
                    <div class="portal-detail-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px;">
                        <div class="portal-detail-block" style="background: var(--admin-bg); padding: 12px 14px; border-radius: 6px; border: 1px solid var(--admin-border);">
                            <span style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase;">Customer Name</span>
                            <div style="font-weight: 600; color: var(--admin-ink); margin-top: 2px;" x-text="selected.name"></div>
                        </div>

                        <div class="portal-detail-block" style="background: var(--admin-bg); padding: 12px 14px; border-radius: 6px; border: 1px solid var(--admin-border);">
                            <span style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase;">Email Address</span>
                            <div style="font-weight: 600; color: var(--admin-ink); margin-top: 2px;">
                                <a :href="'mailto:' + selected.email" style="color: var(--admin-accent-hover); text-decoration: underline;" x-text="selected.email"></a>
                            </div>
                        </div>

                        <div class="portal-detail-block" style="background: var(--admin-bg); padding: 12px 14px; border-radius: 6px; border: 1px solid var(--admin-border);">
                            <span style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase;">Phone / WhatsApp</span>
                            <div style="font-weight: 600; color: var(--admin-ink); margin-top: 2px;" x-text="selected.phone || 'Not provided'"></div>
                        </div>

                        <div class="portal-detail-block" style="background: var(--admin-bg); padding: 12px 14px; border-radius: 6px; border: 1px solid var(--admin-border);">
                            <span style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase;">Received At</span>
                            <div style="font-weight: 600; color: var(--admin-ink); margin-top: 2px;" x-text="new Date(selected.created_at).toLocaleString()"></div>
                        </div>
                    </div>

                    <!-- Subject & Full Message Body -->
                    <div style="margin-bottom: 24px; background: #fff; border: 1px solid var(--admin-border); border-radius: 8px; padding: 18px;">
                        <span style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Subject</span>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--admin-ink); margin: 4px 0 16px;" x-text="selected.subject || 'General Inquiry'"></h4>

                        <span style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Message Content</span>
                        <div style="margin-top: 6px; padding: 14px; background: var(--admin-bg); border-radius: 6px; border: 1px solid var(--admin-border); font-size: 14px; line-height: 1.6; color: var(--admin-ink-soft); white-space: pre-wrap;" x-text="selected.message"></div>
                    </div>

                    <!-- Quick Customer Reply Tools -->
                    <div style="margin-bottom: 24px; padding: 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #166534; margin: 0 0 10px; text-transform: uppercase; letter-spacing: 0.05em;">Quick Customer Response</h4>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a :href="'mailto:' + selected.email + '?subject=' + encodeURIComponent('Re: ' + (selected.subject || 'Inquiry') + ' - Leede Fusion')"
                               class="btn-table-action"
                               style="background: #0284c7; color: #fff; border-color: #0284c7; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;"
                               @click="editStatus = 'responded'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                                Reply via Email
                            </a>

                            <template x-if="selected.phone">
                                <a :href="'https://wa.me/' + getCleanPhone(selected.phone) + '?text=' + encodeURIComponent('Hi ' + selected.name + ', thank you for contacting Leede Fusion regarding: ' + (selected.subject || 'your inquiry') + '. ')"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="btn-table-action"
                                   style="background: #22c55e; color: #fff; border-color: #22c55e; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;"
                                   @click="editStatus = 'responded'">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Chat on WhatsApp
                                </a>
                            </template>
                        </div>
                    </div>

                    <!-- Status Update & Admin Notes Form -->
                    <form @submit.prevent="saveChanges()">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--admin-text-soft); margin-bottom: 6px;">STATUS</label>
                                <select class="admin-input"
                                        x-model="editStatus"
                                        style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid var(--admin-border-strong); border-radius: 6px; background: #fff; font-size: 13px;">
                                    <option value="unread">Unread</option>
                                    <option value="read">Read</option>
                                    <option value="responded">Responded</option>
                                </select>
                            </div>

                            <div style="display: flex; align-items: flex-end;">
                                <button type="submit"
                                        class="admin-btn-primary"
                                        :disabled="saving"
                                        style="width: 100%; padding: 10px 16px; font-size: 13px; font-weight: 700; cursor: pointer; border-radius: 6px; background: var(--admin-ink); color: #fff; border: none;">
                                    <span x-text="saving ? 'Saving...' : 'Update Status & Notes'"></span>
                                </button>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--admin-text-soft); margin-bottom: 6px;">INTERNAL ADMIN NOTES (OPTIONAL)</label>
                            <textarea class="admin-input"
                                      x-model="adminNotes"
                                      rows="3"
                                      placeholder="e.g. Called customer on 22 Sep, provided fabric swatches."
                                      style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid var(--admin-border-strong); border-radius: 6px; font-size: 13px;"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
