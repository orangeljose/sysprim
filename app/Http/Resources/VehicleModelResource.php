<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Http\Resources\VehicleBrandResource;

/**
 * @mixin VehicleBrand
 */
class VehicleModelResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'class_name' => $this->getClassName(),            
            'vehicle_brand_id' => $this->vehicle_brand_id,
            'name' => $this->name,
            'year' => $this->year,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateString(),
            'vehicleBrand' => new VehicleBrandResource($this->whenLoaded('vehicleBrand')),            
        ];
    }
}
