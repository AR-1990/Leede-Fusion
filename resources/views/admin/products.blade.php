@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Catalog synced from your database')

@section('content')
<div class="admin-products-view"
     @dt-edit-product.window="openEditById($event.detail.id)"
     @dt-delete-product.window="handleDelete($event.detail.id)"
     x-data="{
    total: 0,
    categories: {{ Js::from($categories) }},

    init() {
        window.productsTable = adminDataTable('#products-table', @js(route('admin.data.products')), [
            { data: 'id', name: 'id', render: (id) => '#' + id },
            { data: 'thumb', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'category_name', name: 'category_name' },
            { data: 'price', name: 'price' },
            { data: 'stock', name: 'stock' },
            { data: 'is_featured', name: 'is_featured', searchable: false },
            { data: 'actions', orderable: false, searchable: false, className: 'dt-actions' },
        ], {
            emptyText: 'No products in the catalog yet. Add your first product above.',
            onData: (json) => this.total = json.recordsTotal,
        });
    },

    reloadTable() {
        window.productsTable?.ajax.reload(null, false);
    },

    async openEditById(id) {
        try {
            this.startEdit(await adminRecord('product', id));
        } catch (e) {
            alert(e.message);
        }
    },
    showAdd: false,
    editingId: null,
    saving: false,
    uploadingPrimary: false,
    uploadingGallery: false,
    errorMessage: '',
    primaryError: '',
    galleryError: '',

    form: {
        name: '',
        category_id: '',
        price: '',
        old_price: '',
        description: '',
        image: '',
        galleryUrls: [],
        tag: '',
        stock: 0,
        is_featured: false
    },

    resetForm() {
        this.form = {
            name: '',
            category_id: '',
            price: '',
            old_price: '',
            description: '',
            image: '',
            galleryUrls: [],
            tag: '',
            stock: 0,
            is_featured: false
        };
        this.editingId = null;
        this.errorMessage = '';
        this.primaryError = '';
        this.galleryError = '';
    },

    openCreate() {
        if (this.showAdd && !this.editingId) {
            this.showAdd = false;
            return;
        }
        this.resetForm();
        this.showAdd = true;
    },

    startEdit(product) {
        this.resetForm();
        this.editingId = product.id;
        this.form = {
            name: product.name || '',
            category_id: product.category_id ? String(product.category_id) : '',
            price: product.price != null ? String(product.price) : '',
            old_price: product.old_price != null ? String(product.old_price) : '',
            description: product.description || '',
            image: product.image || '',
            galleryUrls: Array.isArray(product.images) ? [...product.images] : [],
            tag: product.tag || '',
            stock: product.stock ?? 0,
            is_featured: !!product.is_featured
        };
        this.showAdd = true;
        this.$nextTick(() => this.$refs.productForm?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    },

    cancelEdit() {
        this.resetForm();
        this.showAdd = false;
    },

    buildPayload() {
        return {
            name: this.form.name.trim(),
            category_id: this.form.category_id,
            price: String(this.form.price).trim(),
            old_price: String(this.form.old_price || '').trim() || null,
            description: this.form.description.trim() || null,
            image: this.form.image.trim() || null,
            images: this.form.galleryUrls.length ? this.form.galleryUrls : null,
            tag: this.form.tag.trim() || null,
            stock: Number(this.form.stock) || 0,
            is_featured: this.form.is_featured
        };
    },

    handleSubmit() {
        return this.editingId ? this.saveEdit() : this.handleCreate();
    },

    async saveEdit() {
        this.saving = true;
        this.errorMessage = '';

        try {
            const res = await fetch(`/api/products/${this.editingId}`, {
                method: 'PUT',
                headers: {
                    ...this.getAuthHeaders(),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(this.buildPayload())
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                this.errorMessage = data.errors
                    ? Object.values(data.errors).flat().join(' • ')
                    : (data.message || 'Could not save product');
                return;
            }

            this.reloadTable();
            this.cancelEdit();
        } catch (e) {
            this.errorMessage = 'Network error while saving product.';
        } finally {
            this.saving = false;
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

    async uploadPrimary(e) {
        const file = e.target.files?.[0];
        e.target.value = '';
        if (!file) return;

        this.uploadingPrimary = true;
        this.primaryError = '';

        const fd = new FormData();
        fd.append('file', file);
        fd.append('folder', 'products');

        try {
            const res = await fetch('/api/admin/media', {
                method: 'POST',
                headers: this.getAuthHeaders(),
                body: fd
            });
            const data = await res.json();
            if (!res.ok) {
                this.primaryError = data.message || 'Upload failed';
                return;
            }
            if (data.url) {
                this.form.image = data.url;
            }
        } catch (err) {
            this.primaryError = 'Network error during upload.';
        } finally {
            this.uploadingPrimary = false;
        }
    },

    async uploadGallery(e) {
        const file = e.target.files?.[0];
        e.target.value = '';
        if (!file) return;

        this.uploadingGallery = true;
        this.galleryError = '';

        const fd = new FormData();
        fd.append('file', file);
        fd.append('folder', 'gallery');

        try {
            const res = await fetch('/api/admin/media', {
                method: 'POST',
                headers: this.getAuthHeaders(),
                body: fd
            });
            const data = await res.json();
            if (!res.ok) {
                this.galleryError = data.message || 'Gallery upload failed';
                return;
            }
            if (data.url) {
                this.form.galleryUrls.push(data.url);
            }
        } catch (err) {
            this.galleryError = 'Network error during upload.';
        } finally {
            this.uploadingGallery = false;
        }
    },

    removeGalleryItem(idx) {
        this.form.galleryUrls = this.form.galleryUrls.filter((_, i) => i !== idx);
    },

    async handleCreate() {
        this.saving = true;
        this.errorMessage = '';

        const payload = this.buildPayload();

        try {
            const res = await fetch('/api/products', {
                method: 'POST',
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
                    this.errorMessage = data.message || 'Could not create product';
                }
                this.saving = false;
                return;
            }

            // Successfully added
            this.reloadTable();
            this.resetForm();
            this.showAdd = false;
        } catch (e) {
            this.errorMessage = 'Network error while creating product.';
        } finally {
            this.saving = false;
        }
    },

    async handleDelete(id) {
        if (!confirm('Are you sure you want to delete this product?')) return;

        try {
            const res = await fetch(`/api/products/${id}`, {
                method: 'DELETE',
                headers: this.getAuthHeaders()
            });

            if (res.ok) {
                if (this.editingId === id) this.cancelEdit();
                this.reloadTable();
            } else {
                const data = await res.json().catch(() => ({}));
                alert(data.message || 'Failed to delete product.');
            }
        } catch (e) {
            alert('Network error while deleting product.');
        }
    },

    getProductImage(p) {
        if (p.images && Array.isArray(p.images) && p.images[0]) {
            return p.images[0];
        }
        if (p.image) {
            return p.image;
        }
        return '/1.jpg';
    }
}">

    <div class="admin-action-bar">
        <p>Catalog has <strong x-text="total"></strong> items. Upload photos from your device.</p>
        <button type="button" class="btn-admin-primary" @click="openCreate()">
            <span x-text="showAdd && !editingId ? 'Close form' : '+ Add product'"></span>
        </button>
    </div>

    <!-- Create / Edit Product Form -->
    <div x-show="showAdd" x-cloak x-transition x-ref="productForm">
        <form class="admin-surface" :class="{ 'admin-surface-editing': editingId }" @submit.prevent="handleSubmit()">
            <div class="admin-surface-head">
                <h3 class="admin-surface-title" x-text="editingId ? 'EDIT PRODUCT #' + editingId : 'NEW PRODUCT'"></h3>
                <button type="button" class="btn-admin-ghost" @click="cancelEdit()">Close</button>
            </div>

            <div x-show="errorMessage" class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;" x-text="errorMessage"></div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
                <div>
                    <label class="admin-field-label">
                        PRODUCT NAME *
                    </label>
                    <input type="text" class="admin-input admin-field-input" x-model="form.name" placeholder="e.g. Royal Oxford Kurta" required>
                </div>

                <div>
                    <label class="admin-field-label">
                        CATEGORY *
                    </label>
                    <select class="admin-input admin-field-input" x-model="form.category_id" required>
                        <option value="">Select category…</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="admin-field-label">
                        PRICE * (Rs.)
                    </label>
                    <input type="text" class="admin-input admin-field-input" x-model="form.price" placeholder="4500" required>
                </div>

                <div>
                    <label class="admin-field-label">
                        OLD PRICE (Optional)
                    </label>
                    <input type="text" class="admin-input admin-field-input" x-model="form.old_price" placeholder="5500">
                </div>

                <div>
                    <label class="admin-field-label">
                        BADGE / TAG
                    </label>
                    <input type="text" class="admin-input admin-field-input" x-model="form.tag" placeholder="e.g. LUXURY, NEW DROP">
                </div>

                <div>
                    <label class="admin-field-label">
                        STOCK QUANTITY
                    </label>
                    <input type="number" min="0" class="admin-input admin-field-input" x-model.number="form.stock" placeholder="25">
                </div>

                <!-- Primary Image Upload -->
                <div style="grid-column: 1 / -1; margin-top: 8px;">
                    <label class="admin-upload-label">PRIMARY IMAGE</label>
                    <div class="admin-upload-row">
                        <div class="admin-upload-preview" style="width: 120px; height: 140px;">
                            <template x-if="form.image">
                                <img :src="form.image" alt="" class="admin-upload-preview-img">
                            </template>
                            <template x-if="!form.image">
                                <span class="admin-upload-placeholder">No image</span>
                            </template>
                        </div>
                        <div class="admin-upload-actions">
                            <input type="file" 
                                   x-ref="primaryFileInput" 
                                   accept="image/jpeg,image/png,image/gif,image/webp" 
                                   class="admin-upload-input-native"
                                   @change="uploadPrimary($event)">
                            
                            <button type="button" 
                                    class="btn-admin-secondary" 
                                    :disabled="uploadingPrimary" 
                                    @click="$refs.primaryFileInput.click()">
                                <span x-text="uploadingPrimary ? 'Uploading image…' : 'Upload image'"></span>
                            </button>

                            <template x-if="form.image">
                                <button type="button" class="btn-admin-ghost" @click="form.image = ''">
                                    Remove image
                                </button>
                            </template>
                        </div>
                    </div>
                    <p class="admin-upload-hint">JPEG, PNG, WebP or GIF — up to 10 MB.</p>
                    <p x-show="primaryError" class="admin-upload-error" x-text="primaryError"></p>
                </div>

                <!-- Gallery Images Upload -->
                <div style="grid-column: 1 / -1; margin-top: 8px;">
                    <label class="admin-upload-label">GALLERY IMAGES (OPTIONAL)</label>
                    <div class="admin-gallery-grid">
                        <template x-for="(url, idx) in form.galleryUrls" :key="idx">
                            <div class="admin-gallery-tile">
                                <img :src="url" alt="" class="admin-gallery-img">
                                <button type="button" class="admin-gallery-remove" @click="removeGalleryItem(idx)" aria-label="Remove image">
                                    ×
                                </button>
                            </div>
                        </template>

                        <button type="button" 
                                class="admin-gallery-add" 
                                :disabled="uploadingGallery" 
                                @click="$refs.galleryFileInput.click()">
                            <span x-text="uploadingGallery ? '…' : '+'"></span>
                        </button>
                    </div>

                    <input type="file" 
                           x-ref="galleryFileInput" 
                           accept="image/jpeg,image/png,image/gif,image/webp" 
                           class="admin-upload-input-native" 
                           @change="uploadGallery($event)">

                    <p class="admin-upload-hint">Add multiple photos for product detail sliders and quick view.</p>
                    <p x-show="galleryError" class="admin-upload-error" x-text="galleryError"></p>
                </div>

                <!-- Description -->
                <div style="grid-column: 1 / -1;">
                    <label class="admin-field-label">
                        DESCRIPTION
                    </label>
                    <textarea class="admin-input admin-field-input" style="min-height: 90px;" x-model="form.description" placeholder="Handcrafted with 100% Egyptian cotton..."></textarea>
                </div>

                <!-- Featured Checkbox -->
                <div style="grid-column: 1 / -1; display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                    <input type="checkbox" id="is_featured_cb" x-model="form.is_featured" style="width: 16px; height: 16px; cursor: pointer;">
                    <label for="is_featured_cb" class="admin-check-label">
                        Featured on homepage (Highlights in the featured collection section)
                    </label>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn-admin-primary" :disabled="saving"
                        x-text="saving ? 'SAVING…' : (editingId ? 'SAVE CHANGES' : 'CREATE PRODUCT')"></button>
                <button type="button" class="btn-admin-ghost" x-show="editingId" @click="cancelEdit()">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Products Table (server-side) -->
    <div class="admin-table-container admin-dt">
        <table id="products-table" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IMAGE</th>
                    <th>PRODUCT NAME</th>
                    <th>CATEGORY</th>
                    <th>PRICE</th>
                    <th>STOCK</th>
                    <th>FEATURED</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
