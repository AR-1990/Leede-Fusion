@extends('layouts.admin')

@section('title', $post->exists ? 'Edit Article' : 'New Article')
@section('page_title', $post->exists ? 'Edit Article' : 'New Article')
@section('page_subtitle', 'Images can be placed anywhere inside the text: use the image button, paste, or drag & drop')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
@endpush

@section('content')
<div class="post-editor">
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

    <form method="POST"
          action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
          id="post-form"
          x-data="{ cover: @js(old('cover_image', $post->cover_image)), uploading: false, error: '' }">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="post-editor-grid">
            {{-- Main column --}}
            <section class="admin-surface">
                <label class="post-label">TITLE *</label>
                <input type="text" name="title" class="admin-input post-input post-title-input" value="{{ old('title', $post->title) }}" placeholder="e.g. How to choose the perfect bridal fabric" required>

                <label class="post-label">ARTICLE *</label>
                <div id="post-editor" class="post-quill">{!! old('body', $post->body) !!}</div>
                <textarea name="body" id="post-body" hidden>{{ old('body', $post->body) }}</textarea>
                <p class="post-hint" id="post-editor-status">Tip: click the image icon in the toolbar, or paste / drop a picture straight into the text.</p>

                <label class="post-label">SHORT SUMMARY (shown on cards)</label>
                <textarea name="excerpt" class="admin-input post-input" rows="3" maxlength="500" placeholder="One or two sentences. Left empty, the start of the article is used.">{{ old('excerpt', $post->excerpt) }}</textarea>
            </section>

            {{-- Side column --}}
            <aside class="post-side">
                <section class="admin-surface">
                    <h3 class="admin-surface-title">PUBLISH</h3>
                    <label class="post-check">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
                        <span>Published (visible on the site)</span>
                    </label>
                    <label class="post-label">PUBLISH DATE</label>
                    <input type="datetime-local" name="published_at" class="admin-input post-input" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                    <p class="post-hint">A future date schedules the article.</p>
                    <button type="submit" class="btn-admin-primary" style="width: 100%; margin-top: 12px;">SAVE ARTICLE</button>
                    @if ($post->exists && $post->isLive())
                        <a href="{{ route('journal.show', $post) }}" target="_blank" class="btn-admin-ghost" style="display: block; text-align: center; margin-top: 8px; text-decoration: none;">View on site ↗</a>
                    @endif
                </section>

                <section class="admin-surface">
                    <h3 class="admin-surface-title">COVER IMAGE</h3>
                    <input type="hidden" name="cover_image" :value="cover">
                    <div class="admin-upload-preview" style="width: 100%; height: 160px;">
                        <template x-if="cover"><img :src="cover" alt="" class="admin-upload-preview-img"></template>
                        <template x-if="!cover"><span class="admin-upload-placeholder">No cover</span></template>
                    </div>
                    <input type="file" x-ref="coverInput" accept="image/jpeg,image/png,image/gif,image/webp" class="admin-upload-input-native"
                           @change="uploading = true; error = ''; window.uploadJournalImage($event.target.files[0]).then(url => cover = url).catch(e => error = e.message).finally(() => { uploading = false; $event.target.value = ''; })">
                    <div style="display: flex; gap: 8px; margin-top: 10px;">
                        <button type="button" class="btn-admin-secondary" :disabled="uploading" @click="$refs.coverInput.click()" x-text="uploading ? 'Uploading…' : 'Upload cover'"></button>
                        <button type="button" class="btn-admin-ghost" x-show="cover" @click="cover = ''">Remove</button>
                    </div>
                    <div class="admin-upload-error" x-show="error" x-text="error" style="margin-top: 8px;"></div>
                </section>

                <section class="admin-surface">
                    <h3 class="admin-surface-title">DETAILS</h3>
                    <label class="post-label">CATEGORY</label>
                    <input type="text" name="category" class="admin-input post-input" value="{{ old('category', $post->category) }}" placeholder="e.g. Style Journal" list="post-categories">
                    <datalist id="post-categories">
                        @foreach (\App\Models\Post::query()->whereNotNull('category')->distinct()->pluck('category') as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>

                    <label class="post-label">URL SLUG</label>
                    <input type="text" name="slug" class="admin-input post-input" value="{{ old('slug', $post->slug) }}" placeholder="auto from title">
                </section>

                <section class="admin-surface">
                    <h3 class="admin-surface-title">GOOGLE / SEO</h3>
                    <label class="post-label">SEO TITLE</label>
                    <input type="text" name="meta_title" class="admin-input post-input" maxlength="255" value="{{ old('meta_title', $post->meta_title) }}" placeholder="Defaults to the title">
                    <label class="post-label">META DESCRIPTION</label>
                    <textarea name="meta_description" class="admin-input post-input" rows="3" maxlength="320" placeholder="~155 characters shown in Google results">{{ old('meta_description', $post->meta_description) }}</textarea>
                </section>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    window.uploadJournalImage = async function (file) {
        if (!file) throw new Error('No file selected.');
        if (!file.type.startsWith('image/')) throw new Error('Only image files can be uploaded.');
        if (file.size > 10 * 1024 * 1024) throw new Error('Image must be smaller than 10 MB.');

        const data = new FormData();
        data.append('file', file);
        data.append('folder', 'blog');

        const res = await fetch(@js(route('admin.media')), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: data,
        });
        const json = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(json.message || 'Upload failed.');
        return json.url;
    };

    (function () {
        const status = document.getElementById('post-editor-status');
        const quill = new Quill('#post-editor', {
            theme: 'snow',
            placeholder: 'Write your article… images can go anywhere in the text.',
            modules: {
                toolbar: {
                    container: [
                        [{ header: [2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['blockquote', 'link', 'image'],
                        [{ align: [] }],
                        ['clean'],
                    ],
                    handlers: { image: () => pickImage() },
                },
            },
        });

        async function insertImage(file) {
            const range = quill.getSelection(true);
            status.textContent = 'Uploading image…';
            try {
                const url = await window.uploadJournalImage(file);
                quill.insertEmbed(range.index, 'image', url, 'user');
                quill.setSelection(range.index + 1, 0);
                status.textContent = 'Image added.';
            } catch (e) {
                status.textContent = e.message;
            }
        }

        function pickImage() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/jpeg,image/png,image/gif,image/webp';
            input.onchange = () => input.files[0] && insertImage(input.files[0]);
            input.click();
        }

        // Upload pasted / dropped images instead of embedding them as huge base64 strings.
        const root = quill.root;
        root.addEventListener('paste', (e) => {
            const files = [...(e.clipboardData?.files || [])].filter(f => f.type.startsWith('image/'));
            if (!files.length) return;
            e.preventDefault();
            e.stopPropagation();
            files.forEach(insertImage);
        }, true);
        root.addEventListener('drop', (e) => {
            const files = [...(e.dataTransfer?.files || [])].filter(f => f.type.startsWith('image/'));
            if (!files.length) return;
            e.preventDefault();
            e.stopPropagation();
            files.forEach(insertImage);
        }, true);

        document.getElementById('post-form').addEventListener('submit', (e) => {
            const html = quill.root.innerHTML;
            if (quill.getText().trim() === '' && !html.includes('<img')) {
                e.preventDefault();
                status.textContent = 'The article cannot be empty.';
                return;
            }
            document.getElementById('post-body').value = html;
        });
    })();
</script>
@endpush
