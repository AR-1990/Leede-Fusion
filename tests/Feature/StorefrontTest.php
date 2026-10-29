<?php

namespace Tests\Feature;

use App\Models\CustomOrder;
use App\Models\DesignOption;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_render(): void
    {
        $product = Product::first();
        $post = Post::first();

        foreach (['/', '/collections', '/the-house', '/the-atelier', '/the-process', '/the-fit', '/journal', '/contact', '/cart', '/design', '/track', '/login',
            '/collections/'.$product->slug, '/journal/'.$post->slug, '/design?product='.$product->slug] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_product_page_has_seo_and_customize_link(): void
    {
        $product = Product::first();

        $this->get(route('product.show', $product))
            ->assertOk()
            ->assertSee('"@type":"Product"', false)
            ->assertSee(route('design', ['product' => $product->slug]), false)
            ->assertSee('<link rel="canonical"', false);

        $this->get('/collections/does-not-exist')->assertNotFound();
    }

    public function test_products_get_unique_slugs(): void
    {
        $category = Product::first()->category_id;
        $a = Product::create(['category_id' => $category, 'name' => 'Velvet Shawl', 'price' => '5000']);
        $b = Product::create(['category_id' => $category, 'name' => 'Velvet Shawl', 'price' => '5000']);

        $this->assertSame('velvet-shawl', $a->slug);
        $this->assertSame('velvet-shawl-2', $b->slug);
    }

    public function test_sitemap_and_robots(): void
    {
        $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('product.show', Product::first()), false)
            ->assertSee(route('journal.show', Post::first()), false);

        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.route('sitemap'), false)->assertSee('Disallow: /admin');
    }

    public function test_draft_and_scheduled_posts_are_hidden(): void
    {
        $draft = Post::create(['title' => 'Secret Draft', 'slug' => 'secret-draft', 'body' => '<p>x</p>', 'is_published' => false]);
        $future = Post::create(['title' => 'Future Post', 'slug' => 'future-post', 'body' => '<p>x</p>', 'is_published' => true, 'published_at' => now()->addWeek()]);

        $this->get('/journal/secret-draft')->assertNotFound();
        $this->get('/journal/future-post')->assertNotFound();
        $this->get('/journal')->assertDontSee('Secret Draft')->assertDontSee('Future Post');
    }

    public function test_post_body_is_sanitized(): void
    {
        $post = Post::create([
            'title' => 'Safe',
            'slug' => 'safe',
            'body' => '<p onclick="steal()">Hi</p><script>alert(1)</script><img src="/a.webp" onerror="x()"><a href="javascript:alert(1)">l</a>',
            'is_published' => true,
        ]);

        $this->assertStringNotContainsString('<script', $post->body);
        $this->assertStringNotContainsString('onclick', $post->body);
        $this->assertStringNotContainsString('onerror', $post->body);
        $this->assertStringNotContainsString('javascript:', $post->body);
        $this->assertStringContainsString('<img src="/a.webp">', $post->body);
    }

    public function test_guest_checkout_saves_order_with_server_prices(): void
    {
        $product = Product::first();

        $res = $this->postJson('/checkout', [
            'name' => 'Ayesha Khan',
            'phone' => '0300 1234567',
            'city' => 'Lahore',
            'address' => 'House 1, Street 2',
            'items' => [['id' => $product->id, 'quantity' => 2, 'size' => 'M', 'price' => 1]],
        ])->assertCreated()->assertJsonStructure(['reference', 'track_url', 'whatsapp_url']);

        $order = Order::latest('id')->first();
        $this->assertNull($order->user_id);
        $this->assertEquals($product->priceValue() * 2, (float) $order->total_amount, 'Price must come from the database, not the request');
        $this->assertSame('M', $order->items->first()->size);
        $this->assertSame($order->reference(), $res->json('reference'));

        // The buyer's session can open their tracking page straight away.
        $this->get($res->json('track_url'))->assertOk()->assertSee($order->reference());
    }

    public function test_checkout_validates_input(): void
    {
        $this->postJson('/checkout', ['items' => [['id' => 999999, 'quantity' => 1]]])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'phone', 'city', 'address', 'items.0.id']);

        $this->postJson('/checkout', ['name' => 'A', 'phone' => 'abc', 'city' => 'K', 'address' => 'x', 'items' => []])
            ->assertJsonValidationErrors(['phone', 'items']);
    }

    public function test_design_studio_creates_custom_order_with_estimate_and_photos(): void
    {
        Storage::fake('public');
        $garment = DesignOption::where('group', 'garment')->first();
        $fabric = DesignOption::where('group', 'fabric')->where('price', '>', 0)->first();
        $product = Product::first();

        $res = $this->post('/design', [
            'product_id' => $product->id,
            'selections' => ['garment' => $garment->id, 'fabric' => $fabric->id, 'bogus' => 1],
            'size_mode' => 'custom',
            'measurements' => ['chest' => 36, 'waist' => 30, 'nonsense' => 99],
            'reference_images' => [UploadedFile::fake()->image('look.jpg', 600, 800)],
            'notes' => 'Maroon for mehndi',
            'customer_name' => 'Sara',
            'customer_phone' => '+92 300 7654321',
            'city' => 'Karachi',
        ]);

        $order = CustomOrder::latest('id')->first();
        $res->assertRedirect(route('track.show', $order->code));

        $this->assertMatchesRegularExpression('/^LF-[A-Z0-9]{6}$/', $order->code);
        $this->assertSame($product->priceValue() + $garment->price + $fabric->price, $order->estimated_price);
        $this->assertSame(['garment', 'fabric'], array_keys($order->selections));
        $this->assertSame(['chest' => 36, 'waist' => 30], array_map('intval', $order->measurements));
        $this->assertCount(1, $order->reference_images);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $order->reference_images[0]));

        $this->get(route('track.show', $order->code))->assertOk()->assertSee('Your design is with our atelier');
    }

    public function test_design_studio_rejects_empty_and_invalid_submissions(): void
    {
        $this->post('/design', [
            'size_mode' => 'standard',
            'standard_size' => 'M',
            'customer_name' => 'Sara',
            'customer_phone' => '03001234567',
            'city' => 'Karachi',
        ])->assertSessionHasErrors('selections');

        $this->post('/design', ['size_mode' => 'standard', 'customer_phone' => '12'])
            ->assertSessionHasErrors(['standard_size', 'customer_name', 'customer_phone', 'city']);

        $this->assertSame(0, CustomOrder::count());
    }

    public function test_tracking_requires_matching_phone(): void
    {
        $order = CustomOrder::create([
            'customer_name' => 'Hina',
            'customer_phone' => '0300-1112223',
            'city' => 'Karachi',
            'selections' => [],
        ]);

        // A stranger guessing the URL is sent to the lookup form.
        $this->get(route('track.show', $order->code))->assertRedirect(route('track'));

        $this->post('/track', ['code' => $order->code, 'phone' => '03009999999'])->assertSessionHasErrors('code');

        $this->post('/track', ['code' => strtolower($order->code), 'phone' => '+92 300 1112223'])
            ->assertRedirect(route('track.show', $order->code));
        $this->get(route('track.show', $order->code))->assertOk()->assertSee('Request Received');
    }

    public function test_admin_area_is_protected(): void
    {
        $this->get('/admin/settings')->assertRedirect(route('login'));

        $customer = User::where('is_admin', false)->first();
        $this->actingAs($customer)->get('/admin/settings')->assertRedirect(route('home'));
        $this->actingAs($customer)->post('/admin/media', [])->assertRedirect(route('home'));
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::where('is_admin', true)->first();
        $order = CustomOrder::create(['customer_name' => 'Hina', 'customer_phone' => '03001112223', 'city' => 'Karachi', 'selections' => []]);

        foreach (['/admin', '/admin/settings', '/admin/studio-options', '/admin/custom-orders', '/admin/custom-orders/'.$order->id,
            '/admin/posts', '/admin/posts/create', '/admin/posts/'.Post::first()->slug.'/edit'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_admin_can_move_custom_order_through_stages(): void
    {
        $admin = User::where('is_admin', true)->first();
        $order = CustomOrder::create(['customer_name' => 'Hina', 'customer_phone' => '03001112223', 'city' => 'Karachi', 'selections' => []]);

        $this->actingAs($admin)->put(route('admin.custom-orders.update', $order), [
            'stage' => 'stitching',
            'quoted_price' => 12500,
            'admin_note' => 'Embroidery sample approved.',
        ])->assertRedirect();

        $order->refresh();
        $this->assertSame('stitching', $order->stage);
        $this->assertSame(12500, $order->quoted_price);
        $this->assertSame(['received', 'stitching'], array_column($order->stage_history, 'stage'));

        $this->actingAs($admin)->put(route('admin.custom-orders.update', $order), ['stage' => 'not-a-stage'])
            ->assertSessionHasErrors('stage');
    }

    public function test_admin_settings_are_saved_and_validated(): void
    {
        $admin = User::where('is_admin', true)->first();
        $payload = [
            'project' => [
                'name' => 'Leede Fusion', 'phone' => '0311 0000000', 'whatsapp_international' => '+92 311 0000000',
                'email' => 'hello@leede.test', 'location' => 'Karachi', 'hours' => '10 to 8',
                'instagram' => 'https://instagram.com/leedefusion',
            ],
            'hero_slides' => [[
                'title' => 'NEW SEASON', 'image' => '/1.webp', 'theme' => 'dark',
                'primary_btn_text' => 'SHOP', 'primary_btn_url' => '/collections',
            ]],
            'why_shop' => [['title' => 'Quality', 'description' => 'Best fabrics', 'icon' => 'quality']],
        ];

        $this->actingAs($admin)->put(route('admin.settings.update'), $payload)->assertRedirect(route('admin.settings'));

        $saved = Setting::allCached();
        $this->assertSame('hello@leede.test', $saved['project']['email']);
        $this->assertSame('NEW SEASON', $saved['hero_slides'][0]['title']);

        $payload['project']['email'] = 'not-an-email';
        $payload['hero_slides'] = [];
        $this->actingAs($admin)->put(route('admin.settings.update'), $payload)
            ->assertSessionHasErrors(['project.email', 'hero_slides']);
    }

    public function test_admin_can_manage_design_options_and_hidden_ones_disappear(): void
    {
        $admin = User::where('is_admin', true)->first();

        $this->actingAs($admin)->post(route('admin.design-options.store'), [
            'group' => 'sleeves', 'label' => 'Puff Sleeves', 'price' => 700, 'is_active' => 1,
        ])->assertRedirect();
        $this->get('/design')->assertSee('Puff Sleeves');

        $option = DesignOption::where('label', 'Puff Sleeves')->first();
        $this->actingAs($admin)->put(route('admin.design-options.update', $option), [
            'group' => 'sleeves', 'label' => 'Puff Sleeves', 'price' => 700,
        ])->assertRedirect();
        $this->assertFalse($option->fresh()->is_active);
        $this->get('/design')->assertDontSee('Puff Sleeves');

        $this->actingAs($admin)->post(route('admin.design-options.store'), ['group' => 'hats', 'label' => 'X', 'price' => -1])
            ->assertSessionHasErrors(['group', 'price']);
    }

    public function test_admin_can_write_a_post_with_inline_image(): void
    {
        Storage::fake('public');
        $admin = User::where('is_admin', true)->first();

        $upload = $this->actingAs($admin)->postJson(route('admin.media'), [
            'file' => UploadedFile::fake()->image('inline.jpg'),
            'folder' => 'blog',
        ])->assertCreated();

        $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Bridal Fabrics 101',
            'body' => '<p>Intro</p><p><img src="'.$upload->json('url').'"></p>',
            'is_published' => 1,
        ])->assertRedirect();

        $this->get('/journal/bridal-fabrics-101')->assertOk()->assertSee($upload->json('url'), false);
    }

    public function test_contact_form_is_rate_limited(): void
    {
        $payload = ['name' => 'Ali', 'email' => 'ali@example.com', 'message' => 'Hello there, I need a suit.'];
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/contact', $payload)->assertSuccessful();
        }
        $this->postJson('/api/contact', $payload)->assertStatus(429);
    }

    public function test_admin_password_command_creates_and_resets_admin(): void
    {
        $this->artisan('admin:password', ['email' => 'owner@leedefusion.test'])
            ->expectsQuestion('New admin password (hidden)', 'Correct-Horse-42-Battery')
            ->expectsQuestion('Repeat the password', 'Correct-Horse-42-Battery')
            ->assertSuccessful();

        $user = User::where('email', 'owner@leedefusion.test')->first();
        $this->assertTrue($user->is_admin);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Correct-Horse-42-Battery', $user->password));

        $this->artisan('admin:password', ['email' => 'owner@leedefusion.test'])
            ->expectsQuestion('New admin password (hidden)', 'short')
            ->assertFailed();

        $this->artisan('admin:password', ['email' => 'owner@leedefusion.test'])
            ->expectsQuestion('New admin password (hidden)', 'leedefusion1')
            ->expectsQuestion('Repeat the password', 'leedefusion1')
            ->expectsConfirmation('This password contains the brand/domain name and is easy to guess. Use it anyway?', 'no')
            ->assertFailed();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Correct-Horse-42-Battery', $user->fresh()->password));
    }

    public function test_db_check_debug_route_is_gone(): void
    {
        $this->getJson('/api/db-check')->assertNotFound();
    }
}
