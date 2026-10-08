<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatPembelianTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_shows_email_lookup_form_without_session(): void
    {
        $this->get('/riwayat')
            ->assertOk()
            ->assertSee('Lihat Riwayat Pesanan Anda')
            ->assertDontSee('Riwayat untuk');
    }

    public function test_lookup_stores_email_and_shows_empty_history(): void
    {
        $this->post('/riwayat', ['customer_email' => 'budi@example.com'])
            ->assertRedirect('/riwayat');

        $this->withSession(['customer_email' => 'budi@example.com'])
            ->get('/riwayat')
            ->assertOk()
            ->assertSee('budi@example.com')
            ->assertSee('Belum Ada Pesanan');
    }

    public function test_history_only_lists_orders_matching_session_email(): void
    {
        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 30000,
        ]);

        $mine = $this->createOrder('budi@example.com', $product);
        $others = $this->createOrder('orang@example.com', $product);

        $this->withSession(['customer_email' => 'budi@example.com'])
            ->get('/riwayat')
            ->assertOk()
            ->assertSee($mine->order_number)
            ->assertDontSee($others->order_number);
    }

    public function test_forget_clears_session_email(): void
    {
        $this->withSession(['customer_email' => 'budi@example.com'])
            ->post('/riwayat/lupa')
            ->assertRedirect('/riwayat');

        $this->withSession([])
            ->get('/riwayat')
            ->assertSee('Lihat Riwayat Pesanan Anda');
    }

    private function createOrder(string $email, Product $product): Order
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
            'payment_status' => 'paid',
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
