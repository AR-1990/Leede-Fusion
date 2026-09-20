<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'old_price',
        'image',
        'images',
        'tag',
        'is_featured',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            $images = $product->images;
            if ((! $product->image || $product->image === '') && is_array($images) && count($images) > 0) {
                $product->image = $images[0];
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
