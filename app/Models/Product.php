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
        'slug',
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

            if (! $product->slug) {
                $base = \Illuminate\Support\Str::slug($product->name) ?: 'product';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->when($product->exists, fn ($q) => $q->where('id', '!=', $product->id))->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $product->slug = $slug;
            }
        });
    }

    /** Numeric price; the column is a string and may contain commas or "Rs.". */
    public function priceValue(): int
    {
        return (int) preg_replace('/[^0-9]/', '', (string) $this->price);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
