<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::create([
            'registration_number' => 'CA 123-456',
            'make' => 'Toyota',
            'model' => 'Hilux 2.4GD-6',
            'year' => 2023,
            'current_mileage' => 45200,
            'status' => 'active',
        ]);

        Vehicle::create([
            'registration_number' => 'GP 789-ABC',
            'make' => 'Ford',
            'model' => 'Ranger Double Cab',
            'year' => 2022,
            'current_mileage' => 61400,
            'status' => 'active',
        ]);
    }
}
