<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatPembelianTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_shows_all_transactions(): void
    {
        $product = $this->createProduct();
        $first = $this->createOrder('budi@example.com', $product, 'paid');
        $second = $this->createOrder('andi@example.com', $product, 'pending');

        $this->get('/riwayat')
            ->assertOk()
            ->assertSee($first->order_number)
            ->assertSee($second->order_number);
    }

    public function test_history_shows_stats(): void
    {
        $product = $this->createProduct();
        $this->createOrder('budi@example.com', $product, 'paid');
        $this->createOrder('andi@example.com', $product, 'pending');

        $response = $this->get('/riwayat');

        $response->assertOk()
            ->assertSee('Total Transaksi', false)
            ->assertSee('Total Penjualan', false)
            ->assertSee('Rp 70.600'); // 2 orders × 35.300
    }

    public function test_history_filters_by_status(): void
    {
        $product = $this->createProduct();
        $paid = $this->createOrder('budi@example.com', $product, 'paid');
        $pending = $this->createOrder('andi@example.com', $product, 'pending');

        $this->get('/riwayat?status=paid')
            ->assertOk()
            ->assertSee($paid->order_number)
            ->assertDontSee($pending->order_number);
    }

    public function test_history_filters_by_date_range(): void
    {
        $product = $this->createProduct();
        $today = $this->createOrder('budi@example.com', $product, 'paid');
        $old = $this->createOrder('andi@example.com', $product, 'paid');
        $old->forceFill(['created_at' => now()->subDays(10)])->save();

        $this->get('/riwayat?date_from='.now()->subDay()->toDateString())
            ->assertOk()
            ->assertSee($today->order_number)
            ->assertDontSee($old->order_number);
    }

    public function test_history_rejects_invalid_status(): void
    {
        $this->get('/riwayat?status=hacked')->assertStatus(302);
    }

    private function createProduct(): Product
    {
        return Product::create([
            'name' => 'Latte',
            'slug' => 'latte-'.uniqid(),
            'category' => 'espresso',
            'base_price' => 30000,
        ]);
    }

    private function createOrder(string $email, Product $product, string $status): Order
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => 'Budi',
            'customer_email' => $email,
            'customer_phone' => '0812000000',
            'order_type' => 'takeaway',
            'subtotal' => 30000,
            'tax_amount' => 3300,
            'service_fee' => 2000,
            'total_amount' => 35300,
            'payment_status' => $status,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'base_price' => 30000,
            'subtotal' => 30000,
            'selected_options' => [],
        ]);

        return $order;
    }
}
