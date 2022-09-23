<?php

namespace Database\Factories;

use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vehicle_model_id' => associateTo(VehicleModel::class),
            'color' => $this->faker->safeColorName(),
            'plate' => $this->faker->unique()->regexify('/\d{4}[BCDFGHJKMNLPRSTVWXYZ]{3}/'),
        ];
    }
}
