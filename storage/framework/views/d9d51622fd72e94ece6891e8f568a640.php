<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('page_title', 'Products'); ?>
<?php $__env->startSection('page_subtitle', 'Catalog synced from your database'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-products-view" x-data="{
    products: <?php echo e(Js::from($products)); ?>,
    categories: <?php echo e(Js::from($categories)); ?>,
    showAdd: false,
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
        this.errorMessage = '';
        this.primaryError = '';
        this.galleryError = '';
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

        const payload = {
            name: this.form.name.trim(),
            category_id: this.form.category_id,
            price: String(this.form.price).trim(),
            old_price: this.form.old_price.trim() || null,
            description: this.form.description.trim() || null,
            image: this.form.image.trim() || null,
            images: this.form.galleryUrls.length ? this.form.galleryUrls : null,
            tag: this.form.tag.trim() || null,
            stock: Number(this.form.stock) || 0,
            is_featured: this.form.is_featured
        };

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
            this.products.unshift(data);
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
                this.products = this.products.filter(p => p.id !== id);
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
        <p>Catalog has <strong x-text="products.length"></strong> items. Upload photos from your device.</p>
        <button type="button" class="btn-admin-primary" @click="showAdd = !showAdd">
            <span x-text="showAdd ? 'Close form' : '+ Add product'"></span>
        </button>
    </div>

    <!-- Create Product Form -->
    <div x-show="showAdd" x-cloak x-transition>
        <form class="admin-surface" @submit.prevent="handleCreate()">
            <h3 class="admin-surface-title">NEW PRODUCT</h3>

            <div x-show="errorMessage" class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;" x-text="errorMessage"></div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
                <div>
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        PRODUCT NAME *
                    </label>
                    <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="form.name" placeholder="e.g. Royal Oxford Kurta" required>
                </div>

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        CATEGORY *
                    </label>
                    <select class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px; background: #fff;" x-model="form.category_id" required>
                        <option value="">Select category…</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        PRICE * (Rs.)
                    </label>
                    <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="form.price" placeholder="4500" required>
                </div>

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        OLD PRICE (Optional)
                    </label>
                    <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="form.old_price" placeholder="5500">
                </div>

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        BADGE / TAG
                    </label>
                    <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="form.tag" placeholder="e.g. LUXURY, NEW DROP">
                </div>

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        STOCK QUANTITY
                    </label>
                    <input type="number" min="0" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model.number="form.stock" placeholder="25">
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
                    <label style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        DESCRIPTION
                    </label>
                    <textarea class="admin-input" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #cbd5e1; font-size: 13px; min-height: 90px;" x-model="form.description" placeholder="Handcrafted with 100% Egyptian cotton..."></textarea>
                </div>

                <!-- Featured Checkbox -->
                <div style="grid-column: 1 / -1; display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                    <input type="checkbox" id="is_featured_cb" x-model="form.is_featured" style="width: 16px; height: 16px; cursor: pointer;">
                    <label for="is_featured_cb" style="font-size: 13px; font-weight: 600; color: #0f172a; cursor: pointer;">
                        Featured on homepage (Highlights in the featured collection section)
                    </label>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn-admin-primary" :disabled="saving" x-text="saving ? 'CREATING PRODUCT...' : 'CREATE PRODUCT'"></button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="admin-table-container">
        <table class="admin-table">
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
            <tbody>
                <template x-for="product in products" :key="product.id">
                    <tr>
                        <td style="font-weight: 700;" x-text="'#' + product.id"></td>
                        <td>
                            <img :src="getProductImage(product)" alt="" style="width: 46px; height: 56px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; display: block;">
                        </td>
                        <td style="font-weight: 600;" x-text="product.name"></td>
                        <td style="color: #64748b;" x-text="product.category?.name || 'Uncategorized'"></td>
                        <td style="font-weight: 700;" x-text="'Rs. ' + Number(product.price).toLocaleString()"></td>
                        <td x-text="product.stock ?? 0"></td>
                        <td>
                            <span class="status-badge" :class="product.is_featured ? 'completed' : 'pending'" x-text="product.is_featured ? 'YES' : 'NO'"></span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button type="button" class="delete-btn" @click="handleDelete(product.id)">
                                    DELETE
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="products.length === 0">
                    <tr>
                        <td colSpan="8" style="text-align: center; padding: 48px; color: #94a3b8;">
                            No products found in the catalog. Add your first product above.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mac/Documents/Wahla-Cloth-House/resources/views/admin/products.blade.php ENDPATH**/ ?>