<?php

namespace Database\Seeders;

use App\Misc\Enums\UserRole;
use App\Misc\Enums\VehicleAvailability;
use App\Models\Booking;
use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // one default admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@twr.com',
            'role' => 'admin',
        ]);

        // one default settings model
        Setting::factory()->create();

        // 1 clear customer credentials, and other 20
        User::factory()->create([
            'email' => 'customer@email.com',
            'role' => UserRole::customer->name,
        ]);
        $customers = User::factory()->count(20)->create([
            'role' => 'customer',
        ]);

        // media
        $sourcePath = database_path('seed-assets/images');
        $destinationPath = storage_path('app/public/images');
        File::ensureDirectoryExists($destinationPath);
        $files = File::files($sourcePath);
        foreach ($files as $file) {
            $filename = $file->getFilename();

            File::copy($file->getRealPath(), $destinationPath . '/' . $filename);

            Media::factory()->create([
                'url' => asset('storage/images/' . $filename),
                'user_id' => $admin->id
            ]);
        }

        // 30 vehicles with status "available"
        Vehicle::factory()->count(30)->create([
            'availability' => VehicleAvailability::available->name,
        ]);

        // 3 vehicles with status "unavailable"
        Vehicle::factory()->count(3)->create([
            'availability' => VehicleAvailability::unavailable->name,
        ]);

        // pick 5 users, make each one from 1 to 2 bookings
        $customers->random(5)->each(function ($customer) {
            Booking::factory()->count(rand(1, 2))->create([
                'user_id' => $customer->id,
            ]);
        });
    }
}
