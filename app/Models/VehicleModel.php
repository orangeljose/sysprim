<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Scopes\NameSortingScope;
use App\Traits\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleModel extends Model
{
    use Filterable, 
        HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'year',
        'vehicle_brand_id',
    ];

    protected $with = ['vehicleBrand'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NameSortingScope());
    }

    /**
     * @return BelongsTo
     */
    public function vehicleBrand(): BelongsTo
    {
        return $this->belongsto(VehicleBrand::class);
    }

    /**
     * @return HasMany
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

}
