<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\SoftDeletes;
use App\Scopes\NameSortingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleBrand extends Model
{
    use Filterable,
        HasFactory,
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
