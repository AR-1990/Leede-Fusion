<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomOrder extends Model
{
    /** Atelier stages shown on the customer's tracking timeline. */
    public const STAGES = [
        'received' => 'Request Received',
        'confirmed' => 'Design & Price Confirmed',
        'cutting' => 'Fabric Cutting',
        'stitching' => 'Stitching',
        'embellishment' => 'Embroidery & Finishing',
        'quality_check' => 'Quality Check',
        'dispatched' => 'Dispatched',
        'delivered' => 'Delivered',
    ];

    public const MEASUREMENT_FIELDS = [
        'chest' => 'Chest / Bust',
        'waist' => 'Waist',
        'hips' => 'Hips',
        'shoulder' => 'Shoulder',
        'sleeve_length' => 'Sleeve Length',
        'shirt_length' => 'Shirt Length',
        'armhole' => 'Armhole',
        'trouser_length' => 'Trouser Length',
    ];

    public const SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    protected $fillable = [
        'code',
        'product_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'city',
        'selections',
        'size_mode',
        'standard_size',
        'measurements',
        'reference_images',
        'notes',
        'needed_by',
        'estimated_price',
        'quoted_price',
        'stage',
        'stage_history',
        'admin_note',
    ];

    protected $casts = [
        'selections' => 'array',
        'measurements' => 'array',
        'reference_images' => 'array',
        'stage_history' => 'array',
        'needed_by' => 'date',
        'estimated_price' => 'integer',
        'quoted_price' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (CustomOrder $order) {
            $order->code ??= static::newCode();
            $order->stage ??= 'received';
            $order->stage_history ??= [['stage' => $order->stage, 'at' => now()->toIso8601String()]];
        });
    }

    public static function newCode(): string
    {
        do {
            $code = 'LF-'.strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function moveTo(string $stage): void
    {
        if ($stage === $this->stage) {
            return;
        }
        $history = $this->stage_history ?? [];
        $history[] = ['stage' => $stage, 'at' => now()->toIso8601String()];
        $this->stage = $stage;
        $this->stage_history = $history;
    }

    public function stageLabel(): string
    {
        return self::STAGES[$this->stage] ?? ucfirst($this->stage);
    }

    /** Timestamp of when each stage was reached, keyed by stage. */
    public function stageTimes(): array
    {
        return collect($this->stage_history ?? [])->mapWithKeys(fn ($h) => [$h['stage'] => $h['at']])->all();
    }

    /** Compare phone numbers by their last 10 digits so 0300… and +92300… match. */
    public static function phoneMatches(?string $a, ?string $b): bool
    {
        $norm = fn ($p) => substr(preg_replace('/\D/', '', (string) $p), -10);

        return $norm($a) !== '' && $norm($a) === $norm($b);
    }
}
