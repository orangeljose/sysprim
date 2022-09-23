<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory,
        BelongsToThrough;

    protected $fillable = [
        'vehicle_model_id',
        'plate'
    ];
    
    protected $casts = [
        'vehicle_model_id' => 'int',
    ];

    /**
     * @param $value
     * @return string
     */
    public static function cleanPlate($value): string
    {
        return Str::of($value)
            ->trim()
            ->replace([' ', '-'], '')
            ->upper()
            ->__toString();
    }

    /**
     * @param $value
     */
    public function setPlateAttribute($value)
    {
        $this->attributes['plate'] = self::cleanPlate($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getPlateAttribute($value): string
    {
        return Str::of($value)
            ->upper()
            ->trim()
            ->__toString();
    }

    /**
     * @return BelongsTo
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function vehicleBrand(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(VehicleBrand::class, VehicleModel::class);
    }
}