<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DesignOptionController extends Controller
{
    public function index(): View
    {
        $options = DesignOption::query()->orderBy('sort_order')->orderBy('id')->get()->groupBy('group');

        return view('admin.design-options', [
            'groups' => DesignOption::GROUPS,
            'options' => $options,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DesignOption::create($this->validated($request));

        return back()->with('status', 'Option added to the Design Studio.');
    }

    public function update(Request $request, DesignOption $designOption): RedirectResponse
    {
        $designOption->update($this->validated($request));

        return back()->with('status', "“{$designOption->label}” updated.");
    }

    public function destroy(DesignOption $designOption): RedirectResponse
    {
        $designOption->delete();

        return back()->with('status', 'Option removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'group' => ['required', Rule::in(array_keys(DesignOption::GROUPS))],
            'label' => 'required|string|max:80',
            'description' => 'nullable|string|max:160',
            'image' => 'nullable|string|max:2048',
            'price' => 'required|integer|min:0|max:10000000',
            'sort_order' => 'nullable|integer|min:0|max:1000',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
