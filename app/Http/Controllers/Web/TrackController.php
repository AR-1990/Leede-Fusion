<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackController extends Controller
{
    /** Shop-order statuses mapped onto a customer-friendly timeline. */
    public const ORDER_STAGES = [
        'pending' => 'Order Placed',
        'processing' => 'Being Prepared',
        'shipped' => 'Shipped',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
    ];

    public function form(): View
    {
        return view('track.form');
    }

    /** Looking up an order needs the reference AND the phone number used on it. */
    public function lookup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20',
            'phone' => 'required|string|max:32',
        ]);

        $code = strtoupper(trim($data['code']));
        $record = $this->find($code);

        if (! $record || ! CustomOrder::phoneMatches($record->customer_phone, $data['phone'])) {
            return back()->withInput()->withErrors(['code' => 'We could not find an order with that reference and phone number.']);
        }

        $request->session()->push('tracked_codes', $code);

        return redirect()->route('track.show', $code);
    }

    public function show(Request $request, string $code): View|RedirectResponse
    {
        $code = strtoupper($code);

        if (! in_array($code, $request->session()->get('tracked_codes', []), true)) {
            return redirect()->route('track')->withInput(['code' => $code]);
        }

        $record = $this->find($code);
        abort_unless($record, 404);

        if ($record instanceof CustomOrder) {
            $stages = CustomOrder::STAGES;
            $current = $record->stage;
            $times = $record->stageTimes();
        } else {
            $record->load('items.product');
            $stages = self::ORDER_STAGES;
            $current = in_array($record->status, ['completed'], true) ? 'delivered' : $record->status;
            $times = [];
        }

        return view('track.show', [
            'record' => $record,
            'code' => $code,
            'isCustom' => $record instanceof CustomOrder,
            'stages' => $stages,
            'current' => $current,
            'times' => $times,
            'cancelled' => $record instanceof Order && $record->status === 'cancelled',
        ]);
    }

    private function find(string $code): CustomOrder|Order|null
    {
        if (preg_match('/^ORD-0*(\d+)$/', $code, $m)) {
            return Order::find((int) $m[1]);
        }

        return CustomOrder::query()->where('code', $code)->first();
    }
}
