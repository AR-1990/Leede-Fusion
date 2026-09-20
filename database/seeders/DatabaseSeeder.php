<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@wahla.test'],
            [
                'name' => 'Store Admin',
                'password' => 'password',
                'is_admin' => true,
            ]
        );

        if (! $admin->is_admin) {
            $admin->forceFill(['is_admin' => true])->save();
        }

        $demo = User::query()->firstOrCreate(
            ['email' => 'customer@wahla.test'],
            [
                'name' => 'Demo Customer',
                'password' => 'password',
                'is_admin' => false,
            ]
        );

        if ($demo->is_admin) {
            $demo->forceFill(['is_admin' => false])->save();
        }

        $categories = [
            [
                'name' => "Men's Collection",
                'slug' => 'mens-collection',
                'description' => 'Premium fabrics and traditional footwear.',
                'image' => '/c1.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => "Women's Suits",
                'slug' => 'womens-suits',
                'description' => 'Elegant 3-piece and 2-piece collections.',
                'image' => '/c2.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kids Wear',
                'slug' => 'kids-wear',
                'description' => 'Trendy and comfortable outfits for children.',
                'image' => '/c3.jpg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Bags, jewellery, and cosmetics.',
                'image' => '/hero-fashion.webp',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $row) {
            Category::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'image' => $row['image'],
                    'sort_order' => $row['sort_order'],
                ]
            );
        }

        $cat = static fn (string $slug): ?int => Category::query()->where('slug', $slug)->value('id');

        $products = [
            ['slug' => 'mens-collection', 'name' => 'Embroidered Kurta Set', 'price' => '8,500', 'old_price' => '9,900', 'image' => '/product-1.webp', 'images' => ['/product-1.webp', '/nd1.jpg'], 'tag' => 'New', 'stock' => 24, 'is_featured' => true],
            ['slug' => 'mens-collection', 'name' => 'Linen Waistcoat', 'price' => '6,200', 'old_price' => null, 'image' => '/nd2.jpg', 'images' => ['/nd2.jpg'], 'tag' => null, 'stock' => 18, 'is_featured' => false],
            ['slug' => 'womens-suits', 'name' => 'Silk 3-Piece Suit', 'price' => '12,400', 'old_price' => '14,000', 'image' => '/product-2.webp', 'images' => ['/product-2.webp', '/nd3.jpg'], 'tag' => 'Sale', 'stock' => 10, 'is_featured' => true],
            ['slug' => 'womens-suits', 'name' => 'Printed Lawn 2-Piece', 'price' => '4,500', 'old_price' => null, 'image' => '/nd1.jpg', 'images' => ['/nd1.jpg'], 'tag' => null, 'stock' => 40, 'is_featured' => false],
            ['slug' => 'kids-wear', 'name' => 'Kids Festive Shalwar Kameez', 'price' => '3,200', 'old_price' => null, 'image' => '/nd2.jpg', 'images' => ['/nd2.jpg'], 'tag' => 'Kids', 'stock' => 30, 'is_featured' => false],
            ['slug' => 'accessories', 'name' => 'Leather Crossbody', 'price' => '5,900', 'old_price' => '6,500', 'image' => '/hero-fashion.webp', 'images' => ['/hero-fashion.webp'], 'tag' => 'Accessory', 'stock' => 15, 'is_featured' => false],
        ];

        foreach ($products as $p) {
            $categoryId = $cat($p['slug']);
            if (! $categoryId) {
                continue;
            }
            Product::query()->updateOrCreate(
                ['category_id' => $categoryId, 'name' => $p['name']],
                [
                    'description' => 'Sample catalog item from DatabaseSeeder.',
                    'price' => $p['price'],
                    'old_price' => $p['old_price'],
                    'image' => $p['image'],
                    'images' => $p['images'],
                    'tag' => $p['tag'],
                    'stock' => $p['stock'],
                    'is_featured' => $p['is_featured'],
                ]
            );
        }
    }
}
