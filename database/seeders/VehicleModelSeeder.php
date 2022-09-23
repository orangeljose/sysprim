<?php

namespace Database\Seeders;

use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{

    private array $models = [
        [
            'name' => '108',
            'vehicle_brand_id' => 1,
        ],
        [
            'name' => '208',
            'vehicle_brand_id' => 1,
        ],
        [
            'name' => '308',
            'vehicle_brand_id' => 1,
        ],
        [
            'name' => '408',
            'vehicle_brand_id' => 1,
        ],
        [
            'name' => '508',
            'vehicle_brand_id' => 1,
        ],
        [
            'name' => '208 GTI',
            'vehicle_brand_id' => 1,
        ],
        [
            'name' => 'Polo',
            'vehicle_brand_id' => 2,
        ],
        [
            'name' => 'Golf',
            'vehicle_brand_id' => 2,
        ],
        [
            'name' => 'Golf GTI',
            'vehicle_brand_id' => 2,
        ],
        [
            'name' => 'Passat',
            'vehicle_brand_id' => 2,
        ],
        [
            'name' => 'Up',
            'vehicle_brand_id' => 2,
        ],
        [
            'name' => 'Tuareg',
            'vehicle_brand_id' => 2,
        ],
        [
            'name' => 'Mazda2',
            'vehicle_brand_id' => 3,
        ],
        [
            'name' => 'Mazda3',
            'vehicle_brand_id' => 3,
        ],
        [
            'name' => 'Mazda6',
            'vehicle_brand_id' => 3,
        ],
        [
            'name' => 'Ceed',
            'vehicle_brand_id' => 4,
        ],
        [
            'name' => 'Picanto',
            'vehicle_brand_id' => 4,
        ],
        [
            'name' => 'Rio',
            'vehicle_brand_id' => 4,
        ],
        [
            'name' => 'Soul',
            'vehicle_brand_id' => 4,
        ],
        [
            'name' => 'Carens',
            'vehicle_brand_id' => 4,
        ],
        [
            'name' => 'Citigo',
            'vehicle_brand_id' => 5,
        ],
        [
            'name' => 'Fabia',
            'vehicle_brand_id' => 5,
        ],
        [
            'name' => 'Spaceback',
            'vehicle_brand_id' => 5,
        ],
        [
            'name' => 'Rapid',
            'vehicle_brand_id' => 5,
        ],
        [
            'name' => 'Octavia',
            'vehicle_brand_id' => 5,
        ],
        [
            'name' => 'A1',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'A3',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'A4',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'A5',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'A6',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'A7',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'A8',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'Q2',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'Q3',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'R8',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'TT',
            'vehicle_brand_id' => 6,
        ],
        [
            'name' => 'Stelvio',
            'vehicle_brand_id' => 7,
        ],
        [
            'name' => 'Stelvio Super',
            'vehicle_brand_id' => 7,
        ],
        [
            'name' => 'Giulia',
            'vehicle_brand_id' => 7,
        ],
        [
            'name' => 'Giulietta',
            'vehicle_brand_id' => 7,
        ],
        [
            'name' => '4c',
            'vehicle_brand_id' => 7,
        ],
        [
            'name' => 'Quadrifoglio',
            'vehicle_brand_id' => 7,
        ],
        [
            'name' => 'Qubo',
            'vehicle_brand_id' => 8,
        ],
        [
            'name' => 'Punto',
            'vehicle_brand_id' => 8,
        ],
        [
            'name' => 'Dobló',
            'vehicle_brand_id' => 8,
        ],
        [
            'name' => '500',
            'vehicle_brand_id' => 8,
        ],
        [
            'name' => '500L',
            'vehicle_brand_id' => 8,
        ],
        [
            'name' => '124 Spider',
            'vehicle_brand_id' => 8,
        ],
        [
            'name' => 'C1',
            'vehicle_brand_id' => 9,
        ],
        [
            'name' => 'C3',
            'vehicle_brand_id' => 9,
        ],
        [
            'name' => 'C-Elysée',
            'vehicle_brand_id' => 9,
        ],
        [
            'name' => 'C4-Cactus',
            'vehicle_brand_id' => 9,
        ],
        [
            'name' => 'C4',
            'vehicle_brand_id' => 9,
        ],
        [
            'name' => 'C4-Picasso',
            'vehicle_brand_id' => 9,
        ],
        [
            'name' => 'Spacetourer',
            'vehicle_brand_id' => 9,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
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
        return collect($this->models)->each(function ($data) {
            try {
                VehicleModel::create([
                    'vehicle_brand_id' => $data['vehicle_brand_id'],
                    'name' => $data['name'],
                    'year' => random_int(1850, 2022)
                ]);
            } catch (Exception $exception) {
                dump($exception->getMessage());
            }
        });
    }
}
