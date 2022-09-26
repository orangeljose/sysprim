<?php

namespace App\Http\Resources;

use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @mixin VehicleBrand
 */
class VehicleBrandResource extends ApiResource
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
            'name' => $this->name,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateString(),
        ];
    }
}
