<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_product_index_lists_products(): void
    {
        Product::create([
            'name' => 'Cappuccino',
            'slug' => 'cappuccino',
            'category' => 'espresso',
            'base_price' => 32000,
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/products')
            ->assertOk()
            ->assertSee('Cappuccino');
    }

    public function test_admin_can_create_product(): void
    {
        $this->actingAs($this->admin())->post('/admin/products', [
            'name' => 'Caramel Latte',
            'category' => 'espresso',
            'description' => 'Latte dengan karamel',
            'base_price' => 38000,
            'image_url' => 'https://example.com/latte.jpg',
            'is_available' => '1',
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'name' => 'Caramel Latte',
            'slug' => 'caramel-latte',
            'category' => 'espresso',
            'base_price' => 38000,
            'is_available' => true,
        ]);
    }

    public function test_product_validation_requires_name_and_category(): void
    {
        $this->actingAs($this->admin())
            ->from('/admin/products/create')
            ->post('/admin/products', ['base_price' => 'abc'])
            ->assertRedirect('/admin/products/create')
            ->assertSessionHasErrors(['name', 'category', 'base_price']);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::create([
            'name' => 'Latte',
            'slug' => 'latte',
            'category' => 'espresso',
            'base_price' => 35000,
        ]);

        $this->actingAs($this->admin())->put("/admin/products/{$product->id}", [
            'name' => 'Cafe Latte Special',
            'category' => 'espresso',
            'base_price' => 40000,
            'is_available' => '1',
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Cafe Latte Special',
            'slug' => 'cafe-latte-special',
            'base_price' => 40000,
        ]);
    }

    public function test_admin_can_toggle_availability(): void
    {
        $product = Product::create([
            'name' => 'Mocha',
            'slug' => 'mocha',
            'category' => 'espresso',
            'base_price' => 38000,
            'is_available' => true,
        ]);

        $this->actingAs($this->admin())
            ->patch("/admin/products/{$product->id}/toggle")
            ->assertRedirect();

        $this->assertFalse($product->fresh()->is_available);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::create([
            'name' => 'Croissant',
            'slug' => 'croissant',
            'category' => 'pastry',
            'base_price' => 25000,
        ]);

        $this->actingAs($this->admin())
            ->delete("/admin/products/{$product->id}")
            ->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
