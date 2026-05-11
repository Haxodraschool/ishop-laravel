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
            'img'           => $this->faker->imageUrl(200, 200, 'product'),
            'name'          => $this->faker->words(3, true),
            'desc'          => $this->faker->sentence(),
            'price'         => $this->faker->randomFloat(2, 10000, 5000000),
            'categories_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
        ];
    }
}
