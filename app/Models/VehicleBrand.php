<?php

namespace App\Models;

use App\Scopes\NameSortingScope;
use App\Traits\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleBrand extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new NameSortingScope());
    }

    /**
     * @return HasMany
     */
    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class);
    }
}
