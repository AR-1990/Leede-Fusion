<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    public function index(Request $request): View
    {
        $stage = $request->query('stage');

        // Rows are loaded by the DataTable from admin.data.custom-orders; only the pill counts are needed here.
        $counts = CustomOrder::query()->selectRaw('stage, count(*) as total')->groupBy('stage')->pluck('total', 'stage');

        return view('admin.custom-orders.index', [
            'counts' => $counts,
            'stage' => $stage,
            'stages' => CustomOrder::STAGES,
        ]);
    }

    public function show(CustomOrder $customOrder): View
    {
        return view('admin.custom-orders.show', [
            'order' => $customOrder->load('product'),
            'stages' => CustomOrder::STAGES,
            'fields' => CustomOrder::MEASUREMENT_FIELDS,
        ]);
    }

    public function update(Request $request, CustomOrder $customOrder): RedirectResponse
    {
        $data = $request->validate([
            'stage' => ['required', Rule::in(array_keys(CustomOrder::STAGES))],
            'quoted_price' => 'nullable|integer|min:0|max:100000000',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $customOrder->moveTo($data['stage']);
        $customOrder->quoted_price = $data['quoted_price'] ?? null;
        $customOrder->admin_note = $data['admin_note'] ?? null;
        $customOrder->save();

        return back()->with('status', 'Order updated. Send the customer a WhatsApp update below.');
    }
}
