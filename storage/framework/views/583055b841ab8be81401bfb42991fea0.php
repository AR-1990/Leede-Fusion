<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('page_title', 'Categories'); ?>
<?php $__env->startSection('page_subtitle', 'Collections, cover photos, and sort order'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-categories-view" x-data="{
    categories: <?php echo e(Js::from($categories)); ?>,
    showCreate: false,
    saving: false,
    uploadingCreateImage: false,
    uploadingEditImage: false,
    createError: '',
    editError: '',
    editing: null,

    create: {
        name: '',
        slug: '',
        description: '',
        image: '',
        sort_order: 0
    },

    getAuthHeaders() {
        const token = $store.auth.token || localStorage.getItem('wahla_token') || '';
        return {
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : '',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        };
    },

    async uploadImage(e, target) {
        const file = e.target.files?.[0];
        e.target.value = '';
        if (!file) return;

        if (target === 'create') this.uploadingCreateImage = true;
        if (target === 'edit') this.uploadingEditImage = true;

        const fd = new FormData();
        fd.append('file', file);
        fd.append('folder', 'categories');

        try {
            const res = await fetch('/api/admin/media', {
                method: 'POST',
                headers: this.getAuthHeaders(),
                body: fd
            });
            const data = await res.json();
            if (!res.ok) {
                alert(data.message || 'Image upload failed');
                return;
            }
            if (data.url) {
                if (target === 'create') this.create.image = data.url;
                if (target === 'edit') this.editing.image = data.url;
            }
        } catch (err) {
            alert('Network error uploading image.');
        } finally {
            if (target === 'create') this.uploadingCreateImage = false;
            if (target === 'edit') this.uploadingEditImage = false;
        }
    },

    async handleCreate() {
        this.saving = true;
        this.createError = '';

        const payload = {
            name: this.create.name.trim(),
            slug: this.create.slug.trim() || undefined,
            description: this.create.description.trim() || null,
            image: this.create.image.trim() || null,
            sort_order: Number(this.create.sort_order) || 0
        };

        try {
            const res = await fetch('/api/categories', {
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
                    this.createError = Object.values(data.errors).flat().join(' • ');
                } else {
                    this.createError = data.message || 'Failed to create category';
                }
                this.saving = false;
                return;
            }

            this.categories.push(data);
            this.create = {
                name: '',
                slug: '',
                description: '',
                image: '',
                sort_order: 0
            };
            this.showCreate = false;
        } catch (err) {
            this.createError = 'Network error while creating category.';
        } finally {
            this.saving = false;
        }
    },

    startEdit(cat) {
        this.editing = JSON.parse(JSON.stringify(cat));
        this.editError = '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    cancelEdit() {
        this.editing = null;
        this.editError = '';
    },

    async saveEdit() {
        if (!this.editing) return;
        this.saving = true;
        this.editError = '';

        const payload = {
            name: this.editing.name.trim(),
            slug: this.editing.slug.trim() || undefined,
            description: this.editing.description ? this.editing.description.trim() : null,
            image: this.editing.image ? this.editing.image.trim() : null,
            sort_order: Number(this.editing.sort_order) || 0
        };

        try {
            const res = await fetch(`/api/categories/${this.editing.id}`, {
                method: 'PUT',
                headers: {
                    ...this.getAuthHeaders(),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!res.ok) {
                if (data.errors) {
                    this.editError = Object.values(data.errors).flat().join(' • ');
                } else {
                    this.editError = data.message || 'Failed to update category';
                }
                this.saving = false;
                return;
            }

            const idx = this.categories.findIndex(c => c.id === this.editing.id);
            if (idx !== -1) {
                this.categories[idx] = data;
            }
            this.editing = null;
        } catch (err) {
            this.editError = 'Network error while updating category.';
        } finally {
            this.saving = false;
        }
    },

    async handleDelete(id) {
        if (!confirm('Delete this category? It must have no products.')) return;

        try {
            const res = await fetch(`/api/categories/${id}`, {
                method: 'DELETE',
                headers: this.getAuthHeaders()
            });

            if (res.ok) {
                this.categories = this.categories.filter(c => c.id !== id);
                if (this.editing && this.editing.id === id) {
                    this.editing = null;
                }
            } else {
                const data = await res.json().catch(() => ({}));
                alert(data.message || 'Delete failed');
            }
        } catch (e) {
            alert('Network error while deleting category.');
        }
    }
}">

    <div class="admin-action-bar">
        <p>Manage product collections, upload cover photos, and set display sort order.</p>
        <button type="button" class="btn-admin-primary" @click="showCreate = !showCreate">
            <span x-text="showCreate ? 'Close form' : '+ ADD CATEGORY'"></span>
        </button>
    </div>

    <!-- Edit Category Surface -->
    <div x-show="editing" x-cloak x-transition>
        <section class="admin-surface" style="border: 2px solid var(--admin-accent);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 class="admin-surface-title" style="margin: 0;" x-text="'EDIT CATEGORY #' + (editing?.id || '')"></h3>
                <button type="button" class="btn-admin-ghost" @click="cancelEdit()">Close</button>
            </div>

            <div x-show="editError" class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;" x-text="editError"></div>

            <form @submit.prevent="saveEdit()">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">NAME *</label>
                        <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="editing.name" required>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">SLUG</label>
                        <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="editing.slug">
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">SORT ORDER</label>
                        <input type="number" min="0" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model.number="editing.sort_order">
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">DESCRIPTION</label>
                        <textarea class="admin-input" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #cbd5e1; font-size: 13px; min-height: 70px;" x-model="editing.description"></textarea>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="admin-upload-label">CATEGORY COVER IMAGE</label>
                        <div class="admin-upload-row">
                            <div class="admin-upload-preview" style="width: 110px; height: 110px;">
                                <template x-if="editing.image">
                                    <img :src="editing.image" alt="" class="admin-upload-preview-img">
                                </template>
                                <template x-if="!editing.image">
                                    <span class="admin-upload-placeholder">No image</span>
                                </template>
                            </div>
                            <div class="admin-upload-actions">
                                <input type="file" 
                                       x-ref="editFileInput" 
                                       accept="image/jpeg,image/png,image/gif,image/webp" 
                                       class="admin-upload-input-native"
                                       @change="uploadImage($event, 'edit')">
                                <button type="button" class="btn-admin-secondary" :disabled="uploadingEditImage" @click="$refs.editFileInput.click()">
                                    <span x-text="uploadingEditImage ? 'Uploading…' : 'Change image'"></span>
                                </button>
                                <template x-if="editing.image">
                                    <button type="button" class="btn-admin-ghost" @click="editing.image = ''">Remove image</button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="portal-modal-footer" style="border: none; padding-top: 16px; margin-top: 8px;">
                    <button type="submit" class="btn-admin-primary" :disabled="saving" x-text="saving ? 'SAVING…' : 'SAVE CHANGES'"></button>
                    <button type="button" class="btn-admin-secondary" @click="cancelEdit()">Cancel</button>
                </div>
            </form>
        </section>
    </div>

    <!-- Add Category Surface -->
    <div x-show="showCreate" x-cloak x-transition>
        <section class="admin-surface">
            <h3 class="admin-surface-title">ADD CATEGORY</h3>

            <div x-show="createError" class="admin-upload-error" style="margin-bottom: 16px; font-weight: 600;" x-text="createError"></div>

            <form @submit.prevent="handleCreate()">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; align-items: end;">
                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">NAME *</label>
                        <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="create.name" placeholder="e.g. Shalwar Kameez" required>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">SLUG (Optional)</label>
                        <input type="text" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model="create.slug" placeholder="auto from name">
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">SORT ORDER</label>
                        <input type="number" min="0" class="admin-input" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 13px;" x-model.number="create.sort_order" placeholder="0">
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">DESCRIPTION</label>
                        <textarea class="admin-input" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #cbd5e1; font-size: 13px; min-height: 70px;" x-model="create.description" placeholder="Bespoke luxury tailored suits..."></textarea>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="admin-upload-label">CATEGORY COVER IMAGE</label>
                        <div class="admin-upload-row">
                            <div class="admin-upload-preview" style="width: 110px; height: 110px;">
                                <template x-if="create.image">
                                    <img :src="create.image" alt="" class="admin-upload-preview-img">
                                </template>
                                <template x-if="!create.image">
                                    <span class="admin-upload-placeholder">No image</span>
                                </template>
                            </div>
                            <div class="admin-upload-actions">
                                <input type="file" 
                                       x-ref="createFileInput" 
                                       accept="image/jpeg,image/png,image/gif,image/webp" 
                                       class="admin-upload-input-native"
                                       @change="uploadImage($event, 'create')">
                                <button type="button" class="btn-admin-secondary" :disabled="uploadingCreateImage" @click="$refs.createFileInput.click()">
                                    <span x-text="uploadingCreateImage ? 'Uploading…' : 'Upload cover image'"></span>
                                </button>
                                <template x-if="create.image">
                                    <button type="button" class="btn-admin-ghost" @click="create.image = ''">Remove image</button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-admin-primary" :disabled="saving" x-text="saving ? 'SAVING…' : 'ADD CATEGORY'"></button>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <!-- Categories Table -->
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IMAGE</th>
                    <th>NAME</th>
                    <th>SLUG</th>
                    <th>SORT</th>
                    <th>PRODUCTS</th>
                    <th style="text-align: right;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="cat in categories" :key="cat.id">
                    <tr>
                        <td style="font-weight: 700;" x-text="'#' + cat.id"></td>
                        <td>
                            <img :src="cat.image || '/c1.jpg'" alt="" style="width: 44px; height: 52px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0; display: block;">
                        </td>
                        <td style="font-weight: 600;" x-text="cat.name"></td>
                        <td style="color: #64748b;" x-text="cat.slug"></td>
                        <td x-text="cat.sort_order ?? 0"></td>
                        <td>
                            <span style="background: #f1f5f9; padding: 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; color: #475569;" x-text="(cat.products_count ?? 0) + ' items'"></span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="edit-btn" @click="startEdit(cat)" style="margin-right: 12px; font-weight: 600;">
                                EDIT
                            </button>
                            <button type="button" class="delete-btn" @click="handleDelete(cat.id)" style="font-weight: 600;">
                                DELETE
                            </button>
                        </td>
                    </tr>
                </template>
                <template x-if="categories.length === 0">
                    <tr>
                        <td colSpan="7" style="text-align: center; padding: 48px; color: #94a3b8;">
                            No categories yet. Add your first category above.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/admin/categories.blade.php ENDPATH**/ ?>