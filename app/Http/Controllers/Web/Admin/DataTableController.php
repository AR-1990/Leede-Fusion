<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactInquiry;
use App\Models\CustomOrder;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Support\Catalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Server-side JSON feeds for the admin DataTables. Paging, search and sorting all run in SQL,
 * so the admin stays fast however many rows a table grows to.
 */
class DataTableController extends Controller
{
    private const ORDER_STATUS_LABELS = [
        'pending' => 'Order placed',
        'processing' => 'Preparing',
        'shipped' => 'Shipped',
        'out_for_delivery' => 'Out for delivery',
        'delivered' => 'Delivered',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public function products(): JsonResponse
    {
        $query = Product::query()->with('category:id,name')->select('products.*');

        return DataTables::eloquent($query)
            ->addColumn('thumb', fn (Product $p) => $this->thumb(Catalog::primaryImage($p)))
            ->editColumn('name', fn (Product $p) => '<strong>'.e($p->name).'</strong><small class="dt-sub">'.e($p->slug).'</small>')
            // Plain-text columns are escaped by Yajra; only rawColumns carry hand-escaped HTML.
            ->addColumn('category_name', fn (Product $p) => $p->category?->name ?? 'Uncategorized')
            ->filterColumn('category_name', fn ($q, $kw) => $q->whereHas('category', fn ($c) => $c->where('name', 'like', "%{$kw}%")))
            ->orderColumn('category_name', fn ($q, $dir) => $q->orderBy(
                Category::select('name')->whereColumn('categories.id', 'products.category_id'), $dir
            ))
            ->editColumn('price', fn (Product $p) => 'Rs. '.number_format($p->priceValue()))
            ->orderColumn('price', "CAST(REPLACE(price, ',', '') AS DECIMAL(12,2)) $1")
            ->editColumn('is_featured', fn (Product $p) => $this->badge($p->is_featured ? 'Yes' : 'No', $p->is_featured ? 'completed' : 'pending'))
            ->addColumn('actions', fn (Product $p) => $this->actions([
                ['edit-product', $p->id, 'Edit', 'edit-btn'],
                ['delete-product', $p->id, 'Delete', 'delete-btn'],
            ], '<a class="edit-btn" href="'.e(route('product.show', $p)).'" target="_blank">View</a>'))
            ->rawColumns(['thumb', 'name', 'is_featured', 'actions'])
            ->toJson();
    }

    public function categories(): JsonResponse
    {
        $query = Category::query()->withCount('products');

        return DataTables::eloquent($query)
            ->addColumn('thumb', fn (Category $c) => $this->thumb($c->image ?: '/c1.jpg'))
            ->editColumn('name', fn (Category $c) => '<strong>'.e($c->name).'</strong>')
            ->editColumn('products_count', fn (Category $c) => '<span class="admin-pill">'.(int) $c->products_count.' items</span>')
            ->orderColumn('products_count', 'products_count $1')
            ->addColumn('actions', fn (Category $c) => $this->actions([
                ['edit-category', $c->id, 'Edit', 'edit-btn'],
                ['delete-category', $c->id, 'Delete', 'delete-btn'],
            ]))
            ->rawColumns(['thumb', 'name', 'products_count', 'actions'])
            ->toJson();
    }

    public function orders(): JsonResponse
    {
        $query = Order::query()->with('user:id,name,email')->select('orders.*');

        return DataTables::eloquent($query)
            ->addColumn('reference', fn (Order $o) => '<strong>'.e($o->reference()).'</strong>')
            ->filterColumn('reference', function ($q, $kw) {
                $digits = (int) preg_replace('/\D/', '', $kw);
                $digits > 0 ? $q->where('orders.id', $digits) : $q->whereRaw('1 = 0');
            })
            ->orderColumn('reference', 'orders.id $1')
            ->editColumn('tracking_number', fn (Order $o) => $o->tracking_number ? '<code class="dt-code">'.e($o->tracking_number).'</code>' : '<span class="dt-muted">—</span>')
            ->addColumn('customer', function (Order $o) {
                $name = $o->user?->name ?: ($o->customer_name ?: 'Guest customer');
                $contact = collect([$o->customer_email ?: $o->user?->email ?: $o->customer_phone, $o->city])->filter()->implode(' · ');

                return '<strong>'.e($name).'</strong><small class="dt-sub">'.e($contact).'</small>';
            })
            ->filterColumn('customer', fn ($q, $kw) => $q->where(fn ($w) => $w
                ->where('customer_name', 'like', "%{$kw}%")
                ->orWhere('customer_email', 'like', "%{$kw}%")
                ->orWhere('customer_phone', 'like', "%{$kw}%")
                ->orWhere('city', 'like', "%{$kw}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$kw}%")->orWhere('email', 'like', "%{$kw}%"))))
            ->orderColumn('customer', 'customer_name $1')
            // Rows created in the same second still sort newest-first.
            ->orderColumn('created_at', 'orders.created_at $1, orders.id $1')
            ->editColumn('created_at', fn (Order $o) => $o->created_at?->format('d M Y'))
            ->editColumn('total_amount', fn (Order $o) => 'Rs. '.number_format((float) $o->total_amount))
            ->editColumn('status', fn (Order $o) => $this->badge(self::ORDER_STATUS_LABELS[$o->status] ?? ucfirst((string) $o->status), $o->status))
            ->addColumn('actions', fn (Order $o) => $this->actions([['manage-order', $o->id, 'Manage', 'btn-table-action']]))
            ->rawColumns(['reference', 'tracking_number', 'customer', 'status', 'actions'])
            ->toJson();
    }

    public function inquiries(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $query = ContactInquiry::query()
            ->when(in_array($status, ['unread', 'read', 'responded'], true), fn ($q) => $q->where('status', $status));

        return DataTables::eloquent($query)
            ->setRowClass(fn (ContactInquiry $i) => $i->status === 'unread' ? 'dt-row-unread' : '')
            ->editColumn('name', fn (ContactInquiry $i) => '<strong>'.e($i->name).'</strong><small class="dt-sub">'.e($i->email).'</small>')
            ->filterColumn('name', fn ($q, $kw) => $q->where(fn ($w) => $w->where('name', 'like', "%{$kw}%")->orWhere('email', 'like', "%{$kw}%")))
            ->editColumn('phone', fn (ContactInquiry $i) => $i->phone ? '<code class="dt-code">'.e($i->phone).'</code>' : '<span class="dt-muted">No phone</span>')
            ->editColumn('subject', fn (ContactInquiry $i) => '<strong>'.e($i->subject ?: 'General Inquiry').'</strong><small class="dt-sub dt-ellipsis">'.e($i->message).'</small>')
            ->filterColumn('subject', fn ($q, $kw) => $q->where(fn ($w) => $w->where('subject', 'like', "%{$kw}%")->orWhere('message', 'like', "%{$kw}%")))
            ->orderColumn('created_at', 'created_at $1, id $1')
            ->editColumn('created_at', fn (ContactInquiry $i) => $i->created_at?->format('d M, g:i A'))
            ->editColumn('status', fn (ContactInquiry $i) => $this->badge(ucfirst((string) $i->status), match ($i->status) {
                'unread' => 'pending', 'read' => 'processing', 'responded' => 'completed', default => '',
            }))
            ->addColumn('actions', fn (ContactInquiry $i) => $this->actions([
                ['view-inquiry', $i->id, 'View', 'btn-table-action'],
                ['delete-inquiry', $i->id, 'Delete', 'delete-btn'],
            ]))
            ->with('stats', self::inquiryStats())
            ->rawColumns(['name', 'phone', 'subject', 'status', 'actions'])
            ->toJson();
    }

    public function users(): JsonResponse
    {
        return DataTables::eloquent(User::query()->select(['id', 'name', 'email', 'is_admin', 'created_at']))
            ->editColumn('name', fn (User $u) => '<strong>'.e($u->name).'</strong>')
            ->editColumn('email', fn (User $u) => '<code class="dt-code">'.e($u->email).'</code>')
            ->editColumn('is_admin', fn (User $u) => $this->badge($u->is_admin ? 'Admin' : 'Customer', $u->is_admin ? 'completed' : 'pending'))
            ->editColumn('created_at', fn (User $u) => $u->created_at?->format('d M Y') ?? '—')
            ->rawColumns(['name', 'email', 'is_admin'])
            ->toJson();
    }

    public function posts(): JsonResponse
    {
        return DataTables::eloquent(Post::query())
            ->addColumn('thumb', fn (Post $p) => $this->thumb($p->cover_image ?: '/hero-fashion.webp', 64, 44))
            ->editColumn('title', fn (Post $p) => '<strong>'.e($p->title).'</strong><small class="dt-sub">/journal/'.e($p->slug).'</small>')
            ->filterColumn('title', fn ($q, $kw) => $q->where(fn ($w) => $w->where('title', 'like', "%{$kw}%")->orWhere('slug', 'like', "%{$kw}%")))
            ->editColumn('category', fn (Post $p) => $p->category ?: '—')
            ->editColumn('is_published', fn (Post $p) => match (true) {
                $p->isLive() => '<span class="admin-pill admin-pill-live">Live</span>',
                $p->is_published => '<span class="admin-pill">Scheduled</span>',
                default => '<span class="admin-pill">Draft</span>',
            })
            ->orderColumn('published_at', 'published_at $1, id $1')
            ->editColumn('published_at', fn (Post $p) => $p->published_at?->format('d M Y') ?? '—')
            ->addColumn('actions', function (Post $p) {
                $view = $p->isLive() ? '<a class="edit-btn" href="'.e(route('journal.show', $p)).'" target="_blank">View</a>' : '';

                return '<div class="action-btns">'.$view
                    .'<a class="edit-btn" href="'.e(route('admin.posts.edit', $p)).'">Edit</a>'
                    .'<form method="POST" action="'.e(route('admin.posts.destroy', $p)).'" onsubmit="return confirm(\'Delete this article?\');">'
                    .csrf_field().method_field('DELETE')
                    .'<button type="submit" class="delete-btn">Delete</button></form></div>';
            })
            ->rawColumns(['thumb', 'title', 'is_published', 'actions'])
            ->toJson();
    }

    public function customOrders(Request $request): JsonResponse
    {
        $stage = $request->query('stage');
        $query = CustomOrder::query()
            ->when(array_key_exists((string) $stage, CustomOrder::STAGES), fn ($q) => $q->where('stage', $stage));

        return DataTables::eloquent($query)
            ->editColumn('code', fn (CustomOrder $o) => '<strong>'.e($o->code).'</strong>')
            ->addColumn('customer', fn (CustomOrder $o) => '<strong>'.e($o->customer_name).'</strong><small class="dt-sub">'.e($o->customer_phone.' · '.$o->city).'</small>')
            ->filterColumn('customer', fn ($q, $kw) => $q->where(fn ($w) => $w
                ->where('customer_name', 'like', "%{$kw}%")
                ->orWhere('customer_phone', 'like', "%{$kw}%")
                ->orWhere('city', 'like', "%{$kw}%")))
            ->orderColumn('customer', 'customer_name $1')
            ->addColumn('design', function (CustomOrder $o) {
                $summary = collect($o->selections)->pluck('label')->implode(' · ')
                    ?: ($o->notes ? \Illuminate\Support\Str::limit($o->notes, 60) : 'See reference photos');

                return '<small class="dt-ellipsis">'.e($summary).'</small>';
            })
            ->addColumn('price', fn (CustomOrder $o) => 'Rs. '.number_format($o->quoted_price ?? $o->estimated_price).($o->quoted_price ? '' : '*'))
            ->orderColumn('price', 'COALESCE(quoted_price, estimated_price) $1')
            ->editColumn('stage', fn (CustomOrder $o) => '<span class="admin-pill'.($o->stage === 'received' ? ' admin-pill-live' : '').'">'.e($o->stageLabel()).'</span>')
            ->orderColumn('created_at', 'created_at $1, id $1')
            ->editColumn('created_at', fn (CustomOrder $o) => $o->created_at?->format('d M Y'))
            ->addColumn('actions', fn (CustomOrder $o) => '<a class="edit-btn" href="'.e(route('admin.custom-orders.show', $o)).'">Open</a>')
            ->rawColumns(['code', 'customer', 'design', 'stage', 'actions'])
            ->toJson();
    }

    /** One record as JSON, for the edit / detail panels opened from a table row. */
    public function record(string $type, int $id): JsonResponse
    {
        $record = match ($type) {
            'product' => Product::with('category')->findOrFail($id),
            'category' => Category::withCount('products')->findOrFail($id),
            'order' => Order::with(['items.product', 'user'])->findOrFail($id),
            'inquiry' => ContactInquiry::findOrFail($id),
            default => abort(404),
        };

        return response()->json($record);
    }

    public static function inquiryStats(): array
    {
        $counts = ContactInquiry::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return [
            'total' => (int) $counts->sum(),
            'unread' => (int) ($counts['unread'] ?? 0),
            'read' => (int) ($counts['read'] ?? 0),
            'responded' => (int) ($counts['responded'] ?? 0),
            'today' => ContactInquiry::query()->where('created_at', '>=', now()->startOfDay())->count(),
        ];
    }

    private function thumb(string $src, int $w = 44, int $h = 52): string
    {
        return '<img src="'.e($src).'" alt="" class="dt-thumb" loading="lazy" style="width:'.$w.'px;height:'.$h.'px">';
    }

    private function badge(string $label, ?string $class): string
    {
        return '<span class="status-badge '.e((string) $class).'">'.e($label).'</span>';
    }

    /** @param array<int, array{0:string,1:int,2:string,3:string}> $buttons */
    private function actions(array $buttons, string $prefix = ''): string
    {
        $html = collect($buttons)->map(fn ($b) => '<button type="button" class="'.e($b[3]).'" data-action="'.e($b[0]).'" data-id="'.(int) $b[1].'">'.e($b[2]).'</button>')->implode('');

        return '<div class="action-btns">'.$prefix.$html.'</div>';
    }
}
