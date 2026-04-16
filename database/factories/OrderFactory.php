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
            'user_id' => User::factory(),
            'status' => 'pending',
            'total_amount' => fake()->randomFloat(2, 5, 200),
            'shipping_address' => fake()->address(),
            'order_type' => 'b2c',
            'stripe_session_id' => null,
        ];
    }
}
