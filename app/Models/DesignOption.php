<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DesignOption extends Model
{
    /** Studio steps, in the order the customer walks through them. */
    public const GROUPS = [
        'garment' => 'Garment',
        'fabric' => 'Fabric',
        'neckline' => 'Neckline',
        'sleeves' => 'Sleeves',
        'embellishment' => 'Embellishment',
    ];

    protected $fillable = ['group', 'label', 'description', 'image', 'price', 'sort_order', 'is_active'];

    protected $casts = [
        'price' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
