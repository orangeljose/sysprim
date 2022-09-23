<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as EloquentSoftDeletes;
use Illuminate\Support\Facades\Route;

trait SoftDeletes
{
    use EloquentSoftDeletes;

    /**
     * Retrieve the model for a bound value.
     *
     * Modified from \Illuminate\Database\Eloquent\Model::resolveRouteBinding()
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $column = $field ?? $this->getRouteKeyName();

        $builder = $this->where($column, $value);

        if ($this->isShow()) {
            $builder->withTrashed();
        }

        return $builder->first();
    }

    /**
     * @return bool
     */
    private function isShow(): bool
    {
        $routeNamePart = explode(".", Route::currentRouteName());

        return end($routeNamePart) === 'show';
    }
}
