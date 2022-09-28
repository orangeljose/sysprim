<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use Filterable,
        HasFactory,
        BelongsToThrough;

    protected $fillable = [
        'vehicle_model_id',
        'plate',
        'color',
        'entry_date',
    ];
    
    protected $casts = [
        'vehicle_model_id' => 'int',
    ];

    protected $with = ['vehicleBrand'];

    const PLATE_REGEX = '/^(((H|R)?\d{4}[BCDFGHJKMNLPRSTVWXYZ]{3})|((A|AB|AL|AV|B|BA|BI|BU|C|CA|CC|CS|CE|CO|CR|CU|GC|GE|GI|GR|GU|H|HU|PM|IB|J|L|LE|LO|LU|M|MA|ML|MU|NA|O|OR|OU|P|PO|S|SA|SE|SG|SH|SO|SS|T|TE|TF|TO|V|VA|VI|Z|ZA)\d{4}[ABCDEFGHIJKMNLOPSTUVWXYZ]{2})|((A|AB|AL|AV|B|BA|BI|BU|C|CA|CAC|CC|CAS|CS|CE|CO|CR|CU|FP|GC|GE|GR|H|HU|I|IF|PM|J|L|LE|LO|LU|M|MA|ME|ML|MU|PA|NA|O|OR|P|PO|RM|S|SA|SE|SEG|SG|SHA|SH|SO|SS|T|TA|TE|TER|TE|TF|TEG|TG|TO|V|VA|VI|Z|ZA)\d{1,6}))$/';

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