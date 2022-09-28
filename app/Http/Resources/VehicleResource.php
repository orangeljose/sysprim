<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Illuminate\Http\Request;

/**
 * @mixin Vehicle
 */
class VehicleResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'class_name' => $this->getClassName(),
            'vehicle_model_id' => $this->vehicle_model_id,
            'plate' => $this->plate,
            'color' => $this->color,
            'entry_date' => $this->entry_date,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateString(),
            'vehicleBrand' => new VehicleBrandResource($this->whenLoaded('vehicleBrand')),
            'vehicleModel' => new VehicleModelResource($this->whenLoaded('vehicleModel')),                        
        ];
    }
}
