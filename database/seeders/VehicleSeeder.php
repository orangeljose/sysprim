<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::factory()->count(3)->create()->each(function ($u) {
            $u->vehicleModel()->associate(VehicleModel::factory()->make());
        });
    }
    
}
