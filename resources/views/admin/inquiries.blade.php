@extends('layouts.admin')

@section('title', 'Customer Inquiries')
@section('page_title', 'Customer Inquiries')
@section('page_subtitle', 'Review, manage, and respond to incoming contact messages')

@section('content')
<div class="inquiries-portal-wrap" x-data="{
    inquiries: {{ Js::from($inquiries) }},
    stats: {{ Js::from($stats) }},
    activeFilter: 'all',
    searchQuery: '',
    selected: null,
    editStatus: 'unread',
    adminNotes: '',
    saving: false,
    errorMessage: '',
    successMessage: '',

    init() {
        const params = new URLSearchParams(window.location.search);
        const inquiryId = params.get('view');
        if (inquiryId) {
            const found = this.inquiries.find(i => i.id == inquiryId);
            if (found) this.openDetail(found);
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

    get filteredInquiries() {
        return this.inquiries.filter(item => {
            const matchesFilter = this.activeFilter === 'all' || item.status === this.activeFilter;
            if (!matchesFilter) return false;

            if (!this.searchQuery.trim()) return true;
            const q = this.searchQuery.toLowerCase();
            return (item.name && item.name.toLowerCase().includes(q)) ||
                   (item.email && item.email.toLowerCase().includes(q)) ||
                   (item.phone && item.phone.toLowerCase().includes(q)) ||
                   (item.subject && item.subject.toLowerCase().includes(q)) ||
                   (item.message && item.message.toLowerCase().includes(q));
        });
    },

    getStatusBadgeClass(status) {
        if (status === 'unread') return 'status-badge pending';
        if (status === 'read') return 'status-badge processing';
        if (status === 'responded') return 'status-badge completed';
        return 'status-badge';
    },

    getStatusLabel(status) {
        if (status === 'unread') return 'Unread';
        if (status === 'read') return 'Read';
        if (status === 'responded') return 'Responded';
        return status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
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
                const data = await res.json();
                const idx = this.inquiries.findIndex(i => i.id === id);
                if (idx !== -1) {
                    this.inquiries[idx].status = newStatus;
                    this.recalcStats();
                }
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

            const data = await res.json();
            if (!res.ok) {
                this.errorMessage = data.message || 'Failed to update inquiry.';
                this.saving = false;
                return;
            }

            const idx = this.inquiries.findIndex(i => i.id === this.selected.id);
            if (idx !== -1) {
                this.inquiries[idx].status = this.editStatus;
                this.inquiries[idx].admin_notes = this.adminNotes;
                this.recalcStats();
            }
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
                this.inquiries = this.inquiries.filter(i => i.id !== id);
                this.recalcStats();
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

    recalcStats() {
        this.stats.total = this.inquiries.length;
        this.stats.unread = this.inquiries.filter(i => i.status === 'unread').length;
        this.stats.responded = this.inquiries.filter(i => i.status === 'responded').length;
    },

    getCleanPhone(phone) {
        if (!phone) return '';
        return phone.replace(/[^0-9]/g, '');
    }
}">

    <!-- Stats Overview Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #64748b; text-transform: uppercase; margin-bottom: 6px;">Total Inquiries</p>
            <h3 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0;" x-text="stats.total"></h3>
        </div>

        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid #fed7aa; background: #fffaf5;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #ea580c; text-transform: uppercase; margin-bottom: 6px;">Unread Messages</p>
            <h3 style="font-size: 26px; font-weight: 800; color: #c2410c; margin: 0;" x-text="stats.unread"></h3>
        </div>

        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid #bbf7d0; background: #f0fdf4;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #16a34a; text-transform: uppercase; margin-bottom: 6px;">Responded</p>
            <h3 style="font-size: 26px; font-weight: 800; color: #15803d; margin: 0;" x-text="stats.responded"></h3>
        </div>

        <div class="stat-card admin-surface" style="padding: 18px 20px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #64748b; text-transform: uppercase; margin-bottom: 6px;">Today's Messages</p>
            <h3 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0;" x-text="stats.today"></h3>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="admin-action-bar" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; background: #fff; padding: 14px 18px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button type="button"
                    class="btn-table-action"
                    :style="activeFilter === 'all' ? 'background: #0f172a; color: #fff; border-color: #0f172a;' : 'background: #fff; color: #475569;'"
                    @click="activeFilter = 'all'">
                All (<span x-text="stats.total"></span>)
            </button>
            <button type="button"
                    class="btn-table-action"
                    :style="activeFilter === 'unread' ? 'background: #ea580c; color: #fff; border-color: #ea580c;' : 'background: #fff; color: #475569;'"
                    @click="activeFilter = 'unread'">
                Unread (<span x-text="stats.unread"></span>)
            </button>
            <button type="button"
                    class="btn-table-action"
                    :style="activeFilter === 'read' ? 'background: #0284c7; color: #fff; border-color: #0284c7;' : 'background: #fff; color: #475569;'"
                    @click="activeFilter = 'read'">
                Read
            </button>
            <button type="button"
                    class="btn-table-action"
                    :style="activeFilter === 'responded' ? 'background: #16a34a; color: #fff; border-color: #16a34a;' : 'background: #fff; color: #475569;'"
                    @click="activeFilter = 'responded'">
                Responded (<span x-text="stats.responded"></span>)
            </button>
        </div>

        <div style="position: relative; min-width: 260px;">
            <input type="text"
                   x-model="searchQuery"
                   placeholder="Search by name, email, subject..."
                   class="admin-input"
                   style="width: 100%; box-sizing: border-box; padding: 8px 12px 8px 32px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            <svg style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>
    </div>

    <!-- Inquiries Table -->
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>CUSTOMER</th>
                    <th>CONTACT</th>
                    <th>SUBJECT & PREVIEW</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                    <th style="text-align: right; width: 140px;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="item in filteredInquiries" :key="item.id">
                    <tr :style="item.status === 'unread' ? 'background: #fffbf5;' : ''">
                        <td style="font-weight: 700; color: #64748b;" x-text="'#' + item.id"></td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;" x-text="item.name"></div>
                            <div style="font-size: 11px; color: #64748b;" x-text="item.email"></div>
                        </td>
                        <td>
                            <div x-show="item.phone" style="font-size: 12px; font-family: monospace; color: #0f172a;">
                                <span x-text="item.phone"></span>
                            </div>
                            <div x-show="!item.phone" style="font-size: 12px; color: #94a3b8;">
                                No phone
                            </div>
                        </td>
                        <td style="max-width: 320px;">
                            <div style="font-weight: 600; color: #1e293b; font-size: 13px; margin-bottom: 2px;" x-text="item.subject || 'General Inquiry'"></div>
                            <div style="font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="item.message"></div>
                        </td>
                        <td style="color: #64748b; font-size: 12px; white-space: nowrap;" x-text="new Date(item.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></td>
                        <td>
                            <span :class="getStatusBadgeClass(item.status)" x-text="getStatusLabel(item.status)"></span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <button type="button" class="btn-table-action" @click="openDetail(item)" style="margin-right: 4px;">
                                View
                            </button>
                            <button type="button" class="btn-table-action" @click="deleteInquiry(item.id)" style="color: #ef4444; border-color: #fecaca;">
                                Delete
                            </button>
                        </td>
                    </tr>
                </template>

                <template x-if="filteredInquiries.length === 0">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 12px; color: #cbd5e1;">
                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                            <p style="margin: 0; font-size: 14px; font-weight: 500;">No customer inquiries found.</p>
                        </td>
                    </tr>
                </template>
            </tbody>
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
                        <div class="portal-detail-block" style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Customer Name</span>
                            <div style="font-weight: 600; color: #0f172a; margin-top: 2px;" x-text="selected.name"></div>
                        </div>

                        <div class="portal-detail-block" style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Email Address</span>
                            <div style="font-weight: 600; color: #0f172a; margin-top: 2px;">
                                <a :href="'mailto:' + selected.email" style="color: #2563eb; text-decoration: underline;" x-text="selected.email"></a>
                            </div>
                        </div>

                        <div class="portal-detail-block" style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Phone / WhatsApp</span>
                            <div style="font-weight: 600; color: #0f172a; margin-top: 2px;" x-text="selected.phone || 'Not provided'"></div>
                        </div>

                        <div class="portal-detail-block" style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Received At</span>
                            <div style="font-weight: 600; color: #0f172a; margin-top: 2px;" x-text="new Date(selected.created_at).toLocaleString()"></div>
                        </div>
                    </div>

                    <!-- Subject & Full Message Body -->
                    <div style="margin-bottom: 24px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Subject</span>
                        <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 4px 0 16px;" x-text="selected.subject || 'General Inquiry'"></h4>

                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Message Content</span>
                        <div style="margin-top: 6px; padding: 14px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px; line-height: 1.6; color: #1e293b; white-space: pre-wrap;" x-text="selected.message"></div>
                    </div>

                    <!-- Quick Customer Reply Tools -->
                    <div style="margin-bottom: 24px; padding: 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #166534; margin: 0 0 10px; text-transform: uppercase; letter-spacing: 0.05em;">Quick Customer Response</h4>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a :href="'mailto:' + selected.email + '?subject=' + encodeURIComponent('Re: ' + (selected.subject || 'Inquiry') + ' - Leedee Fusion')"
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
                                <a :href="'https://wa.me/' + getCleanPhone(selected.phone) + '?text=' + encodeURIComponent('Hi ' + selected.name + ', thank you for contacting Leedee Fusion regarding: ' + (selected.subject || 'your inquiry') + '. ')"
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
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">STATUS</label>
                                <select class="admin-input"
                                        x-model="editStatus"
                                        style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background: #fff; font-size: 13px;">
                                    <option value="unread">Unread</option>
                                    <option value="read">Read</option>
                                    <option value="responded">Responded</option>
                                </select>
                            </div>

                            <div style="display: flex; align-items: flex-end;">
                                <button type="submit"
                                        class="admin-btn-primary"
                                        :disabled="saving"
                                        style="width: 100%; padding: 10px 16px; font-size: 13px; font-weight: 700; cursor: pointer; border-radius: 6px; background: #0f172a; color: #fff; border: none;">
                                    <span x-text="saving ? 'Saving...' : 'Update Status & Notes'"></span>
                                </button>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">INTERNAL ADMIN NOTES (OPTIONAL)</label>
                            <textarea class="admin-input"
                                      x-model="adminNotes"
                                      rows="3"
                                      placeholder="e.g. Called customer on 22 Sep, provided fabric swatches."
                                      style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
