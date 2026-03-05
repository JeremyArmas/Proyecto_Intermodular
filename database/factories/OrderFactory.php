<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'cancelled']),
            'total_amount' => $this->faker->randomFloat(2, 20, 500),
            'shipping_address' => $this->faker->address(),
            'order_type' => $this->faker->randomElement(['b2c', 'b2b']),
        ];
    }
}
