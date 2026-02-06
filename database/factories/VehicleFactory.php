<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->word,
            'media' => $this->faker->imageUrl() . ',' . $this->faker->imageUrl(),
            'price_per_hour' => $this->faker->randomFloat(2, 10, 100),
            'availability' => $this->faker->randomElement(Vehicle::Availabilities()),
        ];
    }
}