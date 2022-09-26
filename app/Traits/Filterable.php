<?php

namespace App\Traits;

use App\Exceptions\FilterException;
use App\Filters\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Filterable
{
    private Filter $filter;

    /**
     * Initialize Filter
     */
    public function initializeFilterable()
    {
        $this->filter = $this->getFilter();
    }

    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        $filterClass = 'App\\Filters\\' . class_basename($this) . 'Filter';

        return resolve($filterClass);
    }

    /**
     * @param Builder $builder
     * @param array $inputs
     * @return Builder.
     */
    public function scopeFilter(Builder $builder, array $inputs = []): Builder
    {
        return $this->filter->filter($builder, $inputs);
    }

    /**
     * @param Builder $builder
     * @return LengthAwarePaginator|Collection
     * @throws FilterException
     */
    public function scopeGetOrPaginate(Builder $builder)
    {
        return $this->filter->getOrPaginate($builder);
    }
}
