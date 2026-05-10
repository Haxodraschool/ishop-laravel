<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'img'           => fake()->imageUrl(200, 200, 'product'),
            'name'          => fake()->words(3, true),
            'desc'          => fake()->sentence(),
            'price'         => fake()->randomFloat(2, 10000, 5000000),
            'categories_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
        ];
    }
}
