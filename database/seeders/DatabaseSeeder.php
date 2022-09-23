<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VehicleBrandSeeder::class);
        $this->call(VehicleModelSeeder::class);
        $this->call(VehicleSeeder::class);
    }
}
