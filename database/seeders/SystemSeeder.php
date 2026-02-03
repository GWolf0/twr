<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Makes sure the required models are present
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
    }
}