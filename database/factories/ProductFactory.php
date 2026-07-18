<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 500, 10000);
        $compareAtPrice = fake()->boolean(40) ? $price * fake()->randomFloat(2, 1.1, 1.4) : null;

        return [
            'category_id' => Category::factory(),
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(10),
            'description' => fake()->paragraphs(3, true),
            'price' => $price,
            'compare_at_price' => $compareAtPrice,
            'stock_status' => fake()->randomElement(['in_stock', 'stock_out']),
            'is_best_seller' => fake()->boolean(20),
            'is_featured' => fake()->boolean(25),
            'is_active' => true,
            'sold_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
