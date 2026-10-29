<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
                'image' => '/studio/photos/category-mens-collection.webp',
                'sort_order' => 1,
            ],
            [
                'name' => "Women's Suits",
                'slug' => 'womens-suits',
                'description' => 'Elegant 3-piece and 2-piece collections.',
                'image' => '/c2.webp',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kids Wear',
                'slug' => 'kids-wear',
                'description' => 'Trendy and comfortable outfits for children.',
                'image' => '/studio/photos/category-kids-wear.webp',
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
            ['slug' => 'mens-collection', 'name' => 'Embroidered Kurta Set', 'price' => '8,500', 'old_price' => '9,900', 'image' => '/product-1.webp', 'images' => ['/product-1.webp', '/nd1.webp'], 'tag' => 'New', 'stock' => 24, 'is_featured' => true],
            ['slug' => 'mens-collection', 'name' => 'Linen Waistcoat', 'price' => '6,200', 'old_price' => null, 'image' => '/nd2.webp', 'images' => ['/nd2.webp'], 'tag' => null, 'stock' => 18, 'is_featured' => false],
            ['slug' => 'womens-suits', 'name' => 'Silk 3-Piece Suit', 'price' => '12,400', 'old_price' => '14,000', 'image' => '/product-2.webp', 'images' => ['/product-2.webp', '/nd3.webp'], 'tag' => 'Sale', 'stock' => 10, 'is_featured' => true],
            ['slug' => 'womens-suits', 'name' => 'Printed Lawn 2-Piece', 'price' => '4,500', 'old_price' => null, 'image' => '/nd1.webp', 'images' => ['/nd1.webp'], 'tag' => null, 'stock' => 40, 'is_featured' => false],
            ['slug' => 'kids-wear', 'name' => 'Kids Festive Shalwar Kameez', 'price' => '3,200', 'old_price' => null, 'image' => '/nd2.webp', 'images' => ['/nd2.webp'], 'tag' => 'Kids', 'stock' => 30, 'is_featured' => false],
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

        // Starter Design Studio choices; prices are base stitching/fabric costs in PKR, editable in admin.
        $studio = [
            'garment' => [
                ['Shalwar Kameez', 'Classic 3-piece silhouette', 4500],
                ['Kurta & Trouser', 'Straight kurta with cigarette pants', 4000],
                ['Long Frock / Peshwas', 'Flowing floor-length frock', 7500],
                ['Abaya', 'Bespoke modest abaya', 6500],
                ['Men\'s Kurta Shalwar', 'Band collar kurta with shalwar', 3800],
                ['Kids Outfit', 'Festive or daily wear for children', 2500],
            ],
            'fabric' => [
                ['Lawn', 'Breathable summer cotton', 2500, '/fabric-satin.webp'],
                ['Silk Crepe', 'Fluid drape with soft sheen', 6000, '/fabric-silk-crepe.webp'],
                ['Organza', 'Sheer, structured, festive', 5500, '/fabric-organza.webp'],
                ['Chiffon', 'Light and airy for formals', 4500, null],
                ['I\'ll provide my own fabric', 'Stitching only', 0, null],
            ],
            'neckline' => [
                ['Round', null, 0], ['V-Neck', null, 0], ['Band Collar', null, 300],
                ['Sweetheart', null, 400], ['Boat Neck', null, 300],
            ],
            'sleeves' => [
                ['Full Sleeves', null, 0], ['Three-Quarter', null, 0], ['Bell Sleeves', null, 500],
                ['Cap Sleeves', null, 0], ['Sleeveless', null, 0],
            ],
            'embellishment' => [
                ['None / Minimal', 'Clean tailored finish', 0],
                ['Thread Embroidery', 'Neckline and border work', 3000],
                ['Zari & Dabka', 'Hand embellishment for formals', 8000],
                ['Lace & Piping', 'Delicate trims and finishes', 1500],
            ],
        ];

        foreach ($studio as $group => $rows) {
            foreach ($rows as $i => $row) {
                \App\Models\DesignOption::query()->firstOrCreate(
                    ['group' => $group, 'label' => $row[0]],
                    ['description' => $row[1], 'price' => $row[2], 'image' => $row[3] ?? null, 'sort_order' => $i, 'is_active' => true]
                );
            }
        }

        // Unsplash photos in public/studio/photos/ (see CREDITS.md), with the line-art in public/studio/ as fallback.
        // Only options without an image, or still on a seeded illustration, are touched; admin uploads are kept.
        $illustrations = [
            'Shalwar Kameez' => 'garment-shalwar-kameez', 'Kurta & Trouser' => 'garment-kurta-trouser',
            'Long Frock / Peshwas' => 'garment-long-frock', 'Abaya' => 'garment-abaya',
            'Men\'s Kurta Shalwar' => 'garment-mens-kurta-shalwar', 'Kids Outfit' => 'garment-kids-outfit',
            'Chiffon' => 'fabric-chiffon', 'I\'ll provide my own fabric' => 'fabric-own',
            'Round' => 'neck-round', 'V-Neck' => 'neck-v', 'Band Collar' => 'neck-band-collar',
            'Sweetheart' => 'neck-sweetheart', 'Boat Neck' => 'neck-boat',
            'Full Sleeves' => 'sleeve-full', 'Three-Quarter' => 'sleeve-three-quarter', 'Bell Sleeves' => 'sleeve-bell',
            'Cap Sleeves' => 'sleeve-cap', 'Sleeveless' => 'sleeve-sleeveless',
            'None / Minimal' => 'embellish-none', 'Thread Embroidery' => 'embellish-thread',
            'Zari & Dabka' => 'embellish-zari-dabka', 'Lace & Piping' => 'embellish-lace-piping',
        ];
        foreach ($illustrations as $label => $file) {
            $image = file_exists(public_path("studio/photos/{$file}.webp")) ? "/studio/photos/{$file}.webp" : "/studio/{$file}.svg";
            \App\Models\DesignOption::query()->where('label', $label)
                ->where(fn ($q) => $q->whereNull('image')->orWhere('image', 'like', '/studio/%.svg'))
                ->update(['image' => $image]);
        }

        // Starter journal articles (previously hardcoded in config/content.php).
        foreach (config('content.blog_posts', []) as $post) {
            Post::query()->firstOrCreate(
                ['slug' => $post['slug']],
                [
                    'author_id' => $admin->id,
                    'title' => $post['title'],
                    'category' => $post['category'],
                    'cover_image' => $post['image'],
                    'excerpt' => $post['excerpt'],
                    'body' => '<p>'.e($post['content']).'</p>',
                    'is_published' => true,
                    'published_at' => \Illuminate\Support\Carbon::parse($post['date']),
                ]
            );
        }
    }
}
