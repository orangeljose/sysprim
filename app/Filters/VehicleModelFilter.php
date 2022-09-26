<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class VehicleModelFilter extends Filter
{
    /**
     * @param $id
     *
     * @return Builder
     */
    protected function vehicleBrandId($id): Builder
    {
        return $this->builder->where('vehicle_brand_id', $id);
    }
}
