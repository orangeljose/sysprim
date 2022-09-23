<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vehicle_brand_id' => associateTo(VehicleBrand::class),
            'name' => $this->faker->word,
            'year' => random_int(1850, 2022)
        ];
    }
}
