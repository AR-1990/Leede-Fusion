@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page_title', 'Site Settings')
@section('page_subtitle', 'Contact details, social links and the home page hero, all live on save')

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

    <form method="POST" action="{{ route('admin.settings.update') }}"
          x-data="{
              slides: @js(array_values(old('hero_slides', $heroSlides))),
              why: @js(array_values(old('why_shop', $whyShop))),
              uploading: null,
              async upload(event, index) {
                  const file = event.target.files[0];
                  if (!file) return;
                  this.uploading = index;
                  try {
                      this.slides[index].image = await adminUpload(file);
                  } catch (e) {
                      alert(e.message);
                  } finally {
                      this.uploading = null;
                      event.target.value = '';
                  }
              },
              addSlide() {
                  this.slides.push({ badge: '', title: 'NEW SLIDE TITLE', description: '', image: '/hero-fashion.webp', theme: 'dark', primary_btn_text: 'EXPLORE COLLECTION', primary_btn_url: '/collections', secondary_btn_text: 'START YOUR DESIGN', secondary_btn_url: '/design' });
              },
              move(index, dir) {
                  const to = index + dir;
                  if (to < 0 || to >= this.slides.length) return;
                  const copy = [...this.slides];
                  [copy[index], copy[to]] = [copy[to], copy[index]];
                  this.slides = copy;
              },
          }">
        @csrf
        @method('PUT')

        <section class="admin-surface">
            <h3 class="admin-surface-title">CONTACT & BRAND</h3>
            <div class="settings-grid">
                @foreach ([
                    ['name', 'Brand name', 'text', true],
                    ['phone', 'Phone (as shown on site)', 'text', true],
                    ['whatsapp_international', 'WhatsApp (international, e.g. 923001234567)', 'text', true],
                    ['email', 'Email', 'email', true],
                    ['location', 'Location', 'text', true],
                    ['hours', 'Opening hours', 'text', true],
                    ['instagram', 'Instagram URL', 'url', false],
                    ['facebook', 'Facebook URL', 'url', false],
                    ['tiktok', 'TikTok URL', 'url', false],
                ] as [$key, $label, $type, $required])
                    <label>
                        <span class="post-label">{{ strtoupper($label) }}{{ $required ? ' *' : '' }}</span>
                        <input type="{{ $type }}" name="project[{{ $key }}]" class="admin-input post-input" value="{{ old('project.'.$key, $project[$key] ?? '') }}" @required($required)>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="admin-surface">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <h3 class="admin-surface-title" style="margin: 0;">HOME HERO SLIDES</h3>
                <button type="button" class="btn-admin-secondary" @click="addSlide()" :disabled="slides.length >= 6">+ Add slide</button>
            </div>
            <p class="post-hint">Up to 6 slides. Use /design for the Design Studio and /collections for the shop.</p>

            <template x-for="(slide, i) in slides" :key="i">
                <div class="settings-slide">
                    <div class="settings-slide-media">
                        <img :src="slide.image" alt="">
                        <label class="btn-admin-secondary" style="cursor: pointer; text-align: center;">
                            <span x-text="uploading === i ? 'Uploading…' : 'Change image'"></span>
                            <input type="file" accept="image/jpeg,image/png,image/webp" hidden @change="upload($event, i)">
                        </label>
                        <input type="hidden" :name="`hero_slides[${i}][image]`" :value="slide.image">
                    </div>
                    <div class="settings-grid">
                        <label><span class="post-label">BADGE</span><input class="admin-input post-input" :name="`hero_slides[${i}][badge]`" x-model="slide.badge" maxlength="100"></label>
                        <label><span class="post-label">TITLE *</span><input class="admin-input post-input" :name="`hero_slides[${i}][title]`" x-model="slide.title" maxlength="80" required></label>
                        <label style="grid-column: 1 / -1;"><span class="post-label">DESCRIPTION</span><textarea class="admin-input post-input" rows="2" :name="`hero_slides[${i}][description]`" x-model="slide.description" maxlength="300"></textarea></label>
                        <label><span class="post-label">PRIMARY BUTTON *</span><input class="admin-input post-input" :name="`hero_slides[${i}][primary_btn_text]`" x-model="slide.primary_btn_text" maxlength="40" required></label>
                        <label><span class="post-label">PRIMARY LINK *</span><input class="admin-input post-input" :name="`hero_slides[${i}][primary_btn_url]`" x-model="slide.primary_btn_url" required></label>
                        <label><span class="post-label">SECONDARY BUTTON</span><input class="admin-input post-input" :name="`hero_slides[${i}][secondary_btn_text]`" x-model="slide.secondary_btn_text" maxlength="40"></label>
                        <label><span class="post-label">SECONDARY LINK</span><input class="admin-input post-input" :name="`hero_slides[${i}][secondary_btn_url]`" x-model="slide.secondary_btn_url"></label>
                        <label>
                            <span class="post-label">TEXT THEME</span>
                            <select class="admin-input post-input" :name="`hero_slides[${i}][theme]`" x-model="slide.theme">
                                <option value="dark">Dark image (light text)</option>
                                <option value="light">Light image (dark text)</option>
                            </select>
                        </label>
                        <div class="settings-slide-actions">
                            <button type="button" class="btn-admin-ghost" @click="move(i, -1)" :disabled="i === 0">↑</button>
                            <button type="button" class="btn-admin-ghost" @click="move(i, 1)" :disabled="i === slides.length - 1">↓</button>
                            <button type="button" class="delete-btn" @click="slides.splice(i, 1)" x-show="slides.length > 1">Remove slide</button>
                        </div>
                    </div>
                </div>
            </template>
        </section>

        <section class="admin-surface">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <h3 class="admin-surface-title" style="margin: 0;">WHY SHOP WITH US</h3>
                <button type="button" class="btn-admin-secondary" @click="why.push({ title: '', description: '', icon: 'quality' })" :disabled="why.length >= 6">+ Add point</button>
            </div>
            <template x-for="(item, i) in why" :key="i">
                <div class="settings-grid" style="margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--admin-border);">
                    <label><span class="post-label">TITLE *</span><input class="admin-input post-input" :name="`why_shop[${i}][title]`" x-model="item.title" maxlength="60" required></label>
                    <label>
                        <span class="post-label">ICON</span>
                        <select class="admin-input post-input" :name="`why_shop[${i}][icon]`" x-model="item.icon">
                            <option value="quality">Quality</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </label>
                    <label style="grid-column: 1 / -1;"><span class="post-label">DESCRIPTION *</span><textarea class="admin-input post-input" rows="2" :name="`why_shop[${i}][description]`" x-model="item.description" maxlength="300" required></textarea></label>
                    <div><button type="button" class="delete-btn" @click="why.splice(i, 1)">Remove</button></div>
                </div>
            </template>
        </section>

        <div class="settings-save">
            <button type="submit" class="btn-admin-primary">SAVE SETTINGS</button>
        </div>
    </form>
</div>
@endsection
