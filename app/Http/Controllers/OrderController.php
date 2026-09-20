<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Customers: their orders. Admins: all orders.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Order::query()->with(['items.product']);

        if (! $user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            $query->with('user');
        }

        return response()->json($query->latest()->get());
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if (! $user->is_admin && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $order->load(['items.product']);

        if ($user->is_admin) {
            $order->load('user');
        }

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:32',
            'customer_whatsapp' => 'nullable|string|max:32',
            'shipping_address' => 'required|string|max:2000',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $computed = collect($validated['items'])->sum(fn (array $item) => (float) $item['price'] * (int) $item['quantity']);

        if (round(abs($computed - (float) $validated['total_amount']), 2) > 0.01) {
            return response()->json([
                'message' => 'Total amount does not match line items.',
                'expected_total' => round($computed, 2),
            ], 422);
        }

        return DB::transaction(function () use ($request, $validated) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_whatsapp' => $validated['customer_whatsapp'] ?? null,
                'total_amount' => $validated['total_amount'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'] ?? 'cod',
                'status' => 'pending',
                'tracking_number' => null,
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            $tracking = 'WCH-'.str_pad((string) $order->id, 8, '0', STR_PAD_LEFT);
            $order->forceFill(['tracking_number' => $tracking])->save();

            return response()->json($order->fresh()->load(['items.product']), 201);
        });
    }

    /**
     * Admin: update fulfillment status and/or courier tracking reference.
     */
    public function adminUpdate(Request $request, Order $order)
    {
        $statuses = 'pending,processing,shipped,out_for_delivery,delivered,completed,cancelled';

        $validated = $request->validate([
            'status' => 'sometimes|string|in:'.$statuses,
            'tracking_number' => 'sometimes|nullable|string|max:64|unique:orders,tracking_number,'.$order->id,
        ]);

        $order->update($validated);

        return response()->json($order->fresh()->load(['user', 'items.product']));
    }

    /**
     * @deprecated Use PATCH /admin/orders/{order}
     */
    public function updateStatus(Request $request, Order $order)
    {
        return $this->adminUpdate($request, $order);
    }
}
