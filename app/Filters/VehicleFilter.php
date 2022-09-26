<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class VehicleFilter extends Filter
{
    /**
     * @param array $ids
     *
     * @return Builder
     */
    protected function vehicleBrandIds(array $ids): Builder
    {
        return $this->builder->whereHas('vehicleModel', function (Builder $q) use ($ids) {
            return $q->whereIn('vehicle_brand_id', $ids);
        });
    }

    /**
     * @param array $ids
     *
     * @return Builder
     */
    protected function vehicleModelIds(array $ids): Builder
    {
        return $this->builder->whereIn('vehicle_model_id', $ids);
    }

    /**
     * @param string $value
     *
     * @return Builder
     */
    protected function plate($value): Builder
    {
        return $this->builder->where('plate', 'like', "%$value%");
    }

    /**
     * @param string $value
     *
     * @return Builder
     */
    protected function exactPlate($value): Builder
    {
        return $this->builder->where('plate', "$value");
    }

}
