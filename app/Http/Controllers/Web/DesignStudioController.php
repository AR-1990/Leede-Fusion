<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\DesignOption;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DesignStudioController extends Controller
{
    public function create(Request $request): View
    {
        $groups = DesignOption::query()->active()->get()->groupBy('group');

        // Only show steps that actually have options, in the defined order.
        $steps = collect(DesignOption::GROUPS)
            ->filter(fn ($label, $key) => $groups->has($key))
            ->map(fn ($label, $key) => [
                'key' => $key,
                'label' => $label,
                'options' => $groups[$key]->map(fn (DesignOption $o) => [
                    'id' => $o->id,
                    'label' => $o->label,
                    'description' => $o->description,
                    'image' => $o->image,
                    'price' => $o->price,
                ])->values(),
            ])->values();

        $baseProduct = $request->filled('product')
            ? Product::query()->where('slug', $request->query('product'))->first()
            : null;

        return view('design', [
            'steps' => $steps,
            'baseProduct' => $baseProduct,
            'measurementFields' => CustomOrder::MEASUREMENT_FIELDS,
            'sizes' => CustomOrder::SIZES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $groupKeys = array_keys(DesignOption::GROUPS);

        $data = $request->validate([
            'product_id' => 'nullable|integer|exists:products,id',
            'selections' => 'nullable|array',
            'selections.*' => 'nullable|integer|exists:design_options,id',
            'size_mode' => ['required', Rule::in(['standard', 'custom', 'later'])],
            'standard_size' => ['nullable', 'required_if:size_mode,standard', Rule::in(CustomOrder::SIZES)],
            'measurements' => 'nullable|array',
            'measurements.*' => 'nullable|numeric|min:1|max:120',
            'reference_images' => 'nullable|array|max:4',
            'reference_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'notes' => 'nullable|string|max:2000',
            'needed_by' => 'nullable|date|after:today',
            'customer_name' => 'required|string|max:120',
            'customer_phone' => ['required', 'string', 'max:32', 'regex:/^[0-9+\-\s()]{10,}$/'],
            'customer_email' => 'nullable|email|max:255',
            'city' => 'required|string|max:100',
        ], [
            'customer_phone.regex' => 'Please enter a valid phone / WhatsApp number.',
            'standard_size.required_if' => 'Please pick your size.',
        ]);

        // Resolve each step's chosen option; only keys that are real steps and active options count.
        // Keep the studio's step order regardless of the order the browser sent the fields in.
        $chosenIds = collect($groupKeys)->mapWithKeys(fn ($g) => [$g => $data['selections'][$g] ?? null])->filter();
        $options = DesignOption::query()->active()->whereIn('id', $chosenIds)->get()->keyBy('id');

        $selections = [];
        foreach ($chosenIds as $group => $id) {
            if (($opt = $options->get($id)) && $opt->group === $group) {
                $selections[$group] = ['id' => $opt->id, 'label' => $opt->label, 'price' => $opt->price];
            }
        }

        $product = isset($data['product_id']) ? Product::find($data['product_id']) : null;

        if (! $product && ! $selections && blank($data['notes'] ?? null) && ! $request->hasFile('reference_images')) {
            return back()->withInput()->withErrors(['selections' => 'Choose at least one design option, add a reference photo, or describe your idea.']);
        }

        $estimate = ($product?->priceValue() ?? 0) + collect($selections)->sum('price');

        $images = [];
        foreach ($request->file('reference_images', []) as $file) {
            $images[] = '/storage/'.$file->store('uploads/custom-orders', 'public');
        }

        $measurements = $data['size_mode'] === 'custom'
            ? collect($data['measurements'] ?? [])->only(array_keys(CustomOrder::MEASUREMENT_FIELDS))->filter(fn ($v) => $v !== null && $v !== '')->all()
            : null;

        $order = CustomOrder::create([
            'product_id' => $product?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'city' => $data['city'],
            'selections' => $selections,
            'size_mode' => $data['size_mode'],
            'standard_size' => $data['size_mode'] === 'standard' ? $data['standard_size'] : null,
            'measurements' => $measurements,
            'reference_images' => $images,
            'notes' => $data['notes'] ?? null,
            'needed_by' => $data['needed_by'] ?? null,
            'estimated_price' => $estimate,
        ]);

        $request->session()->push('tracked_codes', $order->code);

        return redirect()->route('track.show', $order->code)->with('just_created', true);
    }
}
