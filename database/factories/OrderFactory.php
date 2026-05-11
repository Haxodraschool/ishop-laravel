<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => 'Order-' . $this->faker->unique()->numerify('######'),
            'desc'     => $this->faker->sentence(),
            'users_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'status'   => $this->faker->randomElement([0, 1, 2, 3]),
            'receiver' => $this->faker->name(),
        ];
    }
}
