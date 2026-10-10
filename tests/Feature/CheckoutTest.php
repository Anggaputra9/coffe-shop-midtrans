<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_only_requires_name(): void
    {
        Http::fake([
            '*' => Http::response(['token' => 'fake-snap-token'], 200),
        ]);

        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 30000,
        ]);

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Budi',
            'order_type' => 'takeaway',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1, 'options' => []],
            ],
        ]);

        $response->assertOk()->assertJson(['success' => true, 'snap_token' => 'fake-snap-token']);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi',
            'customer_email' => null,
            'customer_phone' => null,
            'payment_status' => 'pending',
        ]);
    }

    public function test_checkout_rejects_empty_name(): void
    {
        Http::fake();

        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 30000,
        ]);

        $this->postJson('/checkout', [
            'customer_name' => '',
            'order_type' => 'takeaway',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ])->assertStatus(422)->assertJsonValidationErrors('customer_name');

        Http::assertNothingSent();
    }

    public function test_order_page_shows_order_log_with_time(): void
    {
        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 30000,
        ]);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => 'Budi',
            'order_type' => 'takeaway',
            'subtotal' => 30000,
            'tax_amount' => 3300,
            'service_fee' => 2000,
            'total_amount' => 35300,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->get('/orders/'.$order->uuid)
            ->assertOk()
            ->assertSee('Log Pesanan')
            ->assertSee('Pesanan dibuat')
            ->assertSee('Pembayaran diterima')
            ->assertDontSee('WhatsApp');
    }
}
