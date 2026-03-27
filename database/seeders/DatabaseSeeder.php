<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fuel Types
        $fuelTypes = ['Octane', 'Diesel', 'Petrol', 'LPG', 'CNG'];
        foreach ($fuelTypes as $type) {
            \App\Models\FuelType::create(['name' => $type]);
        }

        // Vehicle Types
        $vehicleTypes = ['Car', 'Motorbike', 'Bus', 'Pickup', 'Truck'];
        foreach ($vehicleTypes as $type) {
            \App\Models\VehicleType::create(['name' => $type]);
        }

        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@fuel.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Operator
        User::factory()->create([
            'name' => 'Operator User',
            'email' => 'operator@fuel.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        // Regular User
        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'user@fuel.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }
}
