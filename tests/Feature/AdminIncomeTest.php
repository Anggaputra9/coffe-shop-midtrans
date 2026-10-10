<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminIncomeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_income_page_shows_paid_stats(): void
    {
        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 30000,
        ]);

        $this->createOrder($product, 'paid');
        $this->createOrder($product, 'pending');

        $this->actingAs($this->admin())
            ->get('/admin/pemasukan')
            ->assertOk()
            ->assertSee('Pemasukan Hari Ini')
            ->assertSee('Rp 35.300') // paid order total
            ->assertSee('1 dari 2 transaksi lunas');
    }

    public function test_income_filters_by_status(): void
    {
        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 30000,
        ]);

        $paid = $this->createOrder($product, 'paid');
        $pending = $this->createOrder($product, 'pending');

        $this->actingAs($this->admin())
            ->get('/admin/pemasukan?status=paid')
            ->assertOk()
            ->assertSee($paid->order_number)
            ->assertDontSee($pending->order_number);
    }

    private function createOrder(Product $product, string $status): Order
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => 'Budi',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '0812000000',
            'order_type' => 'takeaway',
            'subtotal' => 30000,
            'tax_amount' => 3300,
            'service_fee' => 2000,
            'total_amount' => 35300,
            'payment_status' => $status,
            'paid_at' => $status === 'paid' ? now() : null,
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
