<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_whatsapp',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method',
        'tracking_number',
        'city',
        'notes',
    ];

    /** Customer-facing order reference, e.g. ORD-00012. */
    public function reference(): string
    {
        return 'ORD-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
