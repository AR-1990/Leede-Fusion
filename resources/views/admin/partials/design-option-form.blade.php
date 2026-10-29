@php($isOld = ! $option && old('group'))
<form method="POST" action="{{ $action }}" class="settings-grid"
      x-data="{ image: @js($isOld ? old('image') : $option?->image), uploading: false }">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <label>
        <span class="post-label">STEP *</span>
        <select name="group" class="admin-input post-input" required>
            @foreach (\App\Models\DesignOption::GROUPS as $key => $label)
                <option value="{{ $key }}" @selected(($isOld ? old('group') : $option?->group) === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label>
        <span class="post-label">LABEL *</span>
        <input type="text" name="label" class="admin-input post-input" maxlength="80" required value="{{ $isOld ? old('label') : $option?->label }}">
    </label>
    <label>
        <span class="post-label">PRICE (RS.) *</span>
        <input type="number" name="price" min="0" class="admin-input post-input" required value="{{ $isOld ? old('price') : ($option?->price ?? 0) }}">
    </label>
    <label>
        <span class="post-label">SORT ORDER</span>
        <input type="number" name="sort_order" min="0" class="admin-input post-input" value="{{ $isOld ? old('sort_order') : ($option?->sort_order ?? 0) }}">
    </label>
    <label style="grid-column: 1 / -1;">
        <span class="post-label">SHORT DESCRIPTION</span>
        <input type="text" name="description" class="admin-input post-input" maxlength="160" value="{{ $isOld ? old('description') : $option?->description }}">
    </label>
    <div style="grid-column: 1 / -1; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <input type="hidden" name="image" :value="image">
        <div class="admin-upload-preview" style="width: 72px; height: 72px;">
            <template x-if="image"><img :src="image" alt="" class="admin-upload-preview-img"></template>
            <template x-if="!image"><span class="admin-upload-placeholder">No image</span></template>
        </div>
        <label class="btn-admin-secondary" style="cursor: pointer;">
            <span x-text="uploading ? 'Uploading…' : 'Upload image'"></span>
            <input type="file" hidden accept="image/jpeg,image/png,image/webp"
                   @change="uploading = true; adminUpload($event.target.files[0]).then(url => image = url).catch(e => alert(e.message)).finally(() => { uploading = false; $event.target.value = ''; })">
        </label>
        <button type="button" class="btn-admin-ghost" x-show="image" @click="image = ''">Remove image</button>
        <label class="post-check" style="margin-left: auto;">
            <input type="checkbox" name="is_active" value="1" @checked($isOld ? old('is_active') : ($option?->is_active ?? true))>
            <span>Visible to customers</span>
        </label>
        <button type="submit" class="btn-admin-primary">{{ $option ? 'SAVE' : 'ADD OPTION' }}</button>
    </div>
</form>
