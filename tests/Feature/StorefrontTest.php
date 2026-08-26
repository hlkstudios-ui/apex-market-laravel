<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_displays_active_products(): void
    {
        $p = $this->product();
        $this->get('/')->assertOk()->assertSee($p->name);
    }

    public function test_global_navigation_uses_the_luxury_house_hierarchy(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('house-service-bar', escape: false)
            ->assertSee('The house of distinction')
            ->assertSee('Search the collection')
            ->assertSee('Designers A–Z')
            ->assertSee('Private sourcing')
            ->assertSee('ui-icon-box', escape: false)
            ->assertSee('ui-count', escape: false)
            ->assertDontSee('Account &amp; Lists', escape: false)
            ->assertDontSee('class="all-menu"', escape: false);
    }

    public function test_customer_can_add_item_and_complete_checkout(): void
    {
        $p = $this->product();
        $this->post(route('cart.store', $p), ['quantity' => 2])->assertRedirect();
        $this->get(route('cart.index'))->assertOk()->assertSee($p->name);
        $response = $this->post(route('checkout.store'), ['name' => 'Ada Customer', 'email' => 'ada@example.com', 'address' => '1 Market Street', 'city' => 'Toronto', 'postal_code' => 'M5V 1A1', 'country' => 'Canada']);
        $order = Order::firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));
        $this->assertSame(2, $order->items()->first()->quantity);
        $this->assertSame(3, $p->fresh()->stock);
        $this->assertEmpty(session('cart', []));
    }

    public function test_designer_directory_uses_reusable_logos_without_sequence_numbers(): void
    {
        Brand::create(['name' => 'Alaïa', 'slug' => 'alaia', 'niche' => 'Fashion']);
        Brand::create(['name' => 'Berluti', 'slug' => 'berluti', 'niche' => 'Fashion']);

        $this->get(route('brands.index'))
            ->assertOk()
            ->assertDontSee('brand-index', escape: false)
            ->assertSee('brand-niche', escape: false)
            ->assertSee('Discover Alaïa — Fashion')
            ->assertSee('data-brand-logo', escape: false)
            ->assertSee('brand-logo__image', escape: false);
    }

    private function product(): Product
    {
        $c = Category::create(['name' => 'Home', 'slug' => 'home']);

        return Product::create(['category_id' => $c->id, 'name' => 'Test Lamp', 'slug' => 'test-lamp', 'description' => 'A useful lamp.', 'price' => 5000, 'image_url' => 'https://example.com/lamp.jpg', 'stock' => 5, 'active' => true]);
    }
}
