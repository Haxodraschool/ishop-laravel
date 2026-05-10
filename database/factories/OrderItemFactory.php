<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'products_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'amount'      => fake()->numberBetween(1, 10),
            'orders_id'   => Order::inRandomOrder()->first()?->id ?? Order::factory(),
        ];
    }
}
