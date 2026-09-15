<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Espresso-based
            ['name' => 'Espresso', 'category' => 'espresso', 'description' => 'Bold, rich double shot espresso pulled from our signature blend. Pure coffee intensity.', 'base_price' => 22000, 'image_url' => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80'],
            ['name' => 'Cappuccino', 'category' => 'espresso', 'description' => 'Velvety steamed milk meets our rich espresso, topped with silky microfoam art.', 'base_price' => 32000, 'image_url' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=600&q=80'],
            ['name' => 'Café Latte', 'category' => 'espresso', 'description' => 'Smooth and creamy espresso-based latte with perfectly textured steamed milk.', 'base_price' => 35000, 'image_url' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80'],
            ['name' => 'Mocha', 'category' => 'espresso', 'description' => 'Rich espresso blended with premium Belgian chocolate and silky steamed milk.', 'base_price' => 38000, 'image_url' => 'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?w=600&q=80'],
            ['name' => 'Americano', 'category' => 'espresso', 'description' => 'Clean and bold espresso lengthened with hot water. Simple, classic, perfect.', 'base_price' => 25000, 'image_url' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?w=600&q=80'],
            ['name' => 'Flat White', 'category' => 'espresso', 'description' => 'Double ristretto with micro-textured milk. Strong yet velvety smooth.', 'base_price' => 36000, 'image_url' => 'https://images.unsplash.com/photo-1577968897966-3d4325b36b61?w=600&q=80'],

            // Manual Brew
            ['name' => 'V60 Pour Over', 'category' => 'manual_brew', 'description' => 'Hand-poured single origin with delicate clarity. Bright, clean, nuanced.', 'base_price' => 35000, 'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80'],
            ['name' => 'Cold Brew', 'category' => 'manual_brew', 'description' => '18-hour cold steeped concentrate. Smooth, chocolatey, low acidity.', 'base_price' => 32000, 'image_url' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=600&q=80'],
            ['name' => 'French Press', 'category' => 'manual_brew', 'description' => 'Full-immersion brew delivering bold, full-bodied coffee with rich mouthfeel.', 'base_price' => 30000, 'image_url' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=600&q=80'],

            // Non-Coffee
            ['name' => 'Matcha Latte', 'category' => 'non_coffee', 'description' => 'Ceremonial grade Uji matcha whisked with creamy steamed milk.', 'base_price' => 35000, 'image_url' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?w=600&q=80'],
            ['name' => 'Chai Latte', 'category' => 'non_coffee', 'description' => 'Aromatic spiced tea blended with warm steamed milk. Cozy in a cup.', 'base_price' => 33000, 'image_url' => 'https://images.unsplash.com/photo-1557006021-b85faa2bc5e2?w=600&q=80'],
            ['name' => 'Hot Chocolate', 'category' => 'non_coffee', 'description' => 'Premium Valrhona chocolate melted into steamed milk. Pure indulgence.', 'base_price' => 30000, 'image_url' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=600&q=80'],

            // Pastry
            ['name' => 'Croissant', 'category' => 'pastry', 'description' => 'Buttery, flaky French croissant baked fresh every morning.', 'base_price' => 25000, 'image_url' => 'https://images.unsplash.com/photo-1555507036-ab1f4038024a?w=600&q=80'],
            ['name' => 'Banana Bread', 'category' => 'pastry', 'description' => 'Moist, homemade banana bread with walnuts. Perfect coffee companion.', 'base_price' => 28000, 'image_url' => 'https://images.unsplash.com/photo-1605090930601-dc4737ba1109?w=600&q=80'],
            ['name' => 'Cinnamon Roll', 'category' => 'pastry', 'description' => 'Warm, swirled cinnamon roll glazed with vanilla cream cheese icing.', 'base_price' => 30000, 'image_url' => 'https://images.unsplash.com/photo-1509365390695-33aee754301f?w=600&q=80'],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'slug' => Str::slug($product['name']),
                'is_available' => true,
            ]));
        }
    }
}
