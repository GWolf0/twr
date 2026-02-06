<?php

namespace Database\Seeders;

use App\Misc\Enums\VehicleAvailability;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // one default admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@twr.com',
            'role' => 'admin',
        ]);

        // one default settings model
        Setting::factory()->create();

        // 20 users of role "customer"
        $customers = User::factory()->count(20)->create([
            'role' => 'customer',
        ]);

        // 30 vehicles with status "available"
        Vehicle::factory()->count(30)->create([
            'availability' => VehicleAvailability::available->name,
        ]);

        // 3 vehicles with status "unavailable"
        Vehicle::factory()->count(3)->create([
            'availability' => VehicleAvailability::unavailable->name,
        ]);

        // pick 5 users, each one one from 1 to 2 bookings
        $customers->random(5)->each(function ($customer) {
            Booking::factory()->count(rand(1, 2))->create([
                'user_id' => $customer->id,
            ]);
        });
    }
}
