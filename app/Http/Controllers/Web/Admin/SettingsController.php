<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'project' => config('content.project'),
            'heroSlides' => config('content.hero_slides', []),
            'whyShop' => config('content.why_shop', []),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project.name' => 'required|string|max:100',
            'project.phone' => 'required|string|max:32',
            'project.whatsapp_international' => ['required', 'string', 'max:32', 'regex:/^\+?[0-9\s-]{10,}$/'],
            'project.email' => 'required|email|max:255',
            'project.location' => 'required|string|max:255',
            'project.hours' => 'required|string|max:255',
            'project.instagram' => 'nullable|url|max:255',
            'project.facebook' => 'nullable|url|max:255',
            'project.tiktok' => 'nullable|url|max:255',
            'project.announcement' => 'nullable|string|max:160',

            'hero_slides' => 'required|array|min:1|max:6',
            'hero_slides.*.badge' => 'nullable|string|max:100',
            'hero_slides.*.title' => 'required|string|max:80',
            'hero_slides.*.description' => 'nullable|string|max:300',
            'hero_slides.*.image' => 'required|string|max:2048',
            'hero_slides.*.theme' => 'required|in:light,dark',
            'hero_slides.*.primary_btn_text' => 'required|string|max:40',
            'hero_slides.*.primary_btn_url' => 'required|string|max:255',
            'hero_slides.*.secondary_btn_text' => 'nullable|string|max:40',
            'hero_slides.*.secondary_btn_url' => 'nullable|string|max:255',

            'why_shop' => 'nullable|array|max:6',
            'why_shop.*.title' => 'required|string|max:60',
            'why_shop.*.description' => 'required|string|max:300',
            'why_shop.*.icon' => 'required|in:quality,whatsapp,delivery',
        ], [
            'project.whatsapp_international.regex' => 'WhatsApp number should be in international format, e.g. 923001234567.',
        ]);

        Setting::put('project', $data['project']);
        Setting::put('hero_slides', collect($data['hero_slides'])->values()->map(fn ($s, $i) => $s + ['id' => $i + 1])->all());
        Setting::put('why_shop', array_values($data['why_shop'] ?? []));

        return redirect()->route('admin.settings')->with('status', 'Site settings saved. Changes are live.');
    }
}
