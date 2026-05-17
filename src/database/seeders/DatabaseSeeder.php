<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Product 1',
            'slug' => 'product-1',
            'description' => 'Our entry-level digital product. Perfect for getting started with premium digital goods.',
            'currency' => 'btc',
            'price' => 0.0008,
            'price_usd' => 50.00,
            'active' => true,
        ]);

        Product::create([
            'name' => 'Product 2',
            'slug' => 'product-2',
            'description' => 'Our mid-tier product with advanced features and extended support.',
            'currency' => 'btc',
            'price' => 0.0038,
            'price_usd' => 250.00,
            'active' => true,
        ]);

        Product::create([
            'name' => 'Product 3',
            'slug' => 'product-3',
            'description' => 'Our premium flagship product. Includes everything plus priority support and exclusive content.',
            'currency' => 'btc',
            'price' => 0.012,
            'price_usd' => 800.00,
            'active' => true,
        ]);
    }
}
