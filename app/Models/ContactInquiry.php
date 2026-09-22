<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    /**
     * Scope for unread inquiries.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope for sorting latest submissions first.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('id');
    }
}
