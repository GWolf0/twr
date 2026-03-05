<?php

namespace Database\Factories;

use App\Models\Media;
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
        $type = $this->faker->randomElement(Vehicle::Types());
        $mediaChoices = Media::where('url', 'like', '%' . $type . '%')->get();
        $selectedMedia = $mediaChoices->isNotEmpty()
            ? $mediaChoices->random()
            : Media::inRandomOrder()->first();

        return [
            'name' => $this->faker->company,
            'type' => $type,
            'media' => $selectedMedia?->url,
            'price_per_hour' => $this->faker->randomFloat(2, 10, 100),
            'availability' => $this->faker->randomElement(Vehicle::Availabilities()),
        ];
    }
}
