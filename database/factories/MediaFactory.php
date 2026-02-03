<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['image', 'video', 'other']),
            'url' => $this->faker->imageUrl(),
            'size' => $this->faker->numberBetween(100, 5000),
            'user_id' => User::factory(),
        ];
    }
}