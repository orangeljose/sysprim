<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class VehicleBrandSeeder extends Seeder
{
    private array $brands = [
        'Peugeot',
        'Volkswagen',
        'Mazda',
        'Kia',
        'Skoda',
        'Audi',
        'Alfa Romeo',
        'Fiat',
        'Citroen',
        'Opel',
        'Iveco',
        'Seat',
        'Toyota',
        'SSANGYONG',
        'Mercedes',
        'Jeep',
        'Nissan',
        'BMW',
        'Ford',
        'Renault',
        'Dacia',
        'DS',
        'Mini',
        'Hyundai',
        'Smart',
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->manualLoad();
    }

    /**
     * @return Collection
     */
    private function manualLoad()
    {
        return collect($this->brands)->each(function ($brand, $index) {
            try {
                VehicleBrand::updateOrCreate(['id' => $index+1], [
                    'id' => $index+1,
                    'name' => $brand,
                ]);
            } catch (Exception $exception) {
                dump($exception->getMessage());
            }
        });
    }
}
