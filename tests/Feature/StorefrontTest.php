<?php

namespace Tests\Feature;

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

    private function product(): Product
    {
        $c = Category::create(['name' => 'Home', 'slug' => 'home']);

        return Product::create(['category_id' => $c->id, 'name' => 'Test Lamp', 'slug' => 'test-lamp', 'description' => 'A useful lamp.', 'price' => 5000, 'image_url' => 'https://example.com/lamp.jpg', 'stock' => 5, 'active' => true]);
    }
}
