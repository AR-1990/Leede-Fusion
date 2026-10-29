<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Guest checkout from the cart. Prices are re-read from the database, never trusted from the browser.
     * The order is saved first, then the customer is handed to WhatsApp with the order reference.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => ['required', 'string', 'max:32', 'regex:/^[0-9+\-\s()]{10,}$/'],
            'email' => 'nullable|email|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1|max:50',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:20',
            'items.*.size' => 'nullable|string|max:32',
        ]);

        $products = Product::query()->whereIn('id', collect($data['items'])->pluck('id'))->get()->keyBy('id');

        $order = DB::transaction(function () use ($data, $products, $request) {
            $lines = collect($data['items'])->map(fn ($item) => [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? null,
                'price' => $products[$item['id']]->priceValue(),
            ]);

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'customer_name' => $data['name'],
                'customer_email' => $data['email'] ?? $request->user()?->email,
                'customer_phone' => $data['phone'],
                'customer_whatsapp' => $data['phone'],
                'total_amount' => $lines->sum(fn ($l) => $l['price'] * $l['quantity']),
                'status' => 'pending',
                'shipping_address' => $data['address'],
                'city' => $data['city'],
                'notes' => $data['notes'] ?? null,
                'payment_method' => 'cod',
            ]);

            $order->items()->createMany($lines->all());

            return $order;
        });

        $request->session()->push('tracked_codes', $order->reference());

        $summary = $order->items()->with('product')->get()->map(fn ($item) => sprintf(
            '• %s%s × %d',
            $item->product?->name,
            $item->size ? " ({$item->size})" : '',
            $item->quantity
        ))->implode("\n");

        $message = "Assalam o Alaikum Leede Fusion!\nI just placed order {$order->reference()}:\n{$summary}\nTotal: Rs. "
            .number_format((float) $order->total_amount)."\nName: {$order->customer_name}\nCity: {$order->city}";

        return response()->json([
            'reference' => $order->reference(),
            'track_url' => route('track.show', $order->reference()),
            'whatsapp_url' => config('content.project.whatsapp_link').'?text='.rawurlencode($message),
        ], 201);
    }
}
