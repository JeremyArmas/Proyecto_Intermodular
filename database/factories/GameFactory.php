<?php

namespace Database\Factories;

use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        return [
            'title'       => ucwords($title),
            'slug'        => Str::slug($title),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 0, 70),
            'b2b_price'   => null,
            'stock'       => fake()->numberBetween(0, 50),
            'developer'   => fake()->company(),
            'platform_id' => Platform::factory(),
            'is_active'   => true,
            'release_date' => null,
            'trailer_url'  => null,
            'cover_image'  => null,
        ];
    }
}
