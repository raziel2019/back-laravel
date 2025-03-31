<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTariff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(3)->create()->each(function($product) {

            $categories = Category::inRandomOrder()->take(rand(1,3))->pluck('id');
            $product->categories()->attach($categories);
            
            ProductTariff::factory()->create([
                'product_id' => $product->id,
                'start_date' => '2025-01-01',
                'end_date' => '2025-06-01',
                'price' => 6.90,
            ]);
        });    
    }
}
