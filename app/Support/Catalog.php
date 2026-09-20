<?php

namespace App\Support;

use App\Models\Product;

class Catalog
{
    public static function primaryImage(?Product $product): string
    {
        if (! $product) {
            return '/hero-fashion.webp';
        }

        $images = $product->images;
        if (is_array($images)) {
            foreach ($images as $url) {
                if ($url && trim((string) $url) !== '') {
                    return (string) $url;
                }
            }
        }

        if ($product->image && trim((string) $product->image) !== '') {
            return (string) $product->image;
        }

        return '/hero-fashion.webp';
    }

    public static function gallery(Product $product): array
    {
        $images = is_array($product->images) ? array_values(array_filter($product->images)) : [];

        if (count($images) > 0) {
            return $images;
        }

        return [self::primaryImage($product)];
    }

    public static function categoryLabel(Product $product): string
    {
        return $product->category?->name ?? '';
    }
}
