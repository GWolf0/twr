<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'canceled', 'completed']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid', 'refunded']),
            'payment_method' => $this->faker->randomElement(['cash', 'credit_card', 'other']),
            'total_amount' => $this->faker->randomFloat(2, 50, 1000),
        ];
    }
}