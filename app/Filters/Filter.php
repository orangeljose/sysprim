<?php

namespace App\Filters;

use App\Exceptions\FilterException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Support\Str;
use Throwable;

abstract class Filter
{
    private array $errors = [];
    private array $debug = [];

    /**
     * Request special inputs
     */
    private const WITH = 'with'; // Load relationships
    private const COUNTS = 'counts'; // Count relationships
    private const RANGE = 'range'; // Make filtering by a given range
    private const HAVING = 'having'; // Filter by relationship existence
    private const DOESNT_HAVE = 'doesnt_have'; // Filter by relationship absence
    private const ONLY_TRASHED = 'only_trashed'; // Filter by soft deleted registers
    private const SORT = 'sort'; // Ordering
    private const PER_PAGE = 'per_page'; // Determine page size
    private const LIMIT = 'limit'; // Limit results
    private const SELECT = 'select'; // Select specific columns
    private const EXCLUDE = 'exclude'; // Exclude specific columns
    private const DONT_PAGINATE = 'dont_paginate'; // Avoid Pagination
    private const MIN_PREFIX = 'min_'; // Prefix used by front to indicate a min value in a range filter
    private const MAX_PREFIX = 'max_'; // Prefix used by front to indicate a max value in a range filter

    protected Builder $builder;
    protected const PAGE_SIZE = 15;
    protected array $inputs;

    /**
     * @param Builder $builder
     * @param array $inputs
     * @return Builder
     */
    public function filter(Builder $builder, array $inputs = []): Builder
    {
        $this->builder = $builder;
        $this->inputs = $inputs;

        $this->applyFilters($inputs);

        $this->applyRanges($inputs);

        // If the client sends a 'range' key in request, add whereBetween clauses to the filters
        if ($range = $inputs[self::RANGE] ?? false) {
            $this->applyWhereBetween($range);
        }

        // If the client sends a 'doesnt_have' key in request, add a relationship absence filter
        if ($doesntHave = $inputs[self::DOESNT_HAVE] ?? false) {
            $this->applyDoesntHave($doesntHave);
        }

        // If the client sends a 'having' key in request, add a relationship existence filter
        if ($having = $inputs[self::HAVING] ?? false) {
            $this->applyHaving($having);
        }

        // If the client sends a 'only_trashed' key in request, add onlyTrash clause to the filters
        if ($inputs[self::ONLY_TRASHED] ?? false) {
            $this->applyOnlyTrashed();
        }

        // Eager load relations
        if ($relations = $inputs[self::WITH] ?? false) {
            $this->applyRelations($relations);
        }

        // Add relations count
        if ($relations = $inputs[self::COUNTS] ?? false) {
            $this->applyRelationCounts($relations);
        }

        // If the client wants a limited result
        if ($limit = $inputs[self::LIMIT] ?? false) {
            $this->builder->take($limit);
        }

        // If the client wants specific columns
        if ($select = $inputs[self::SELECT] ?? false) {
            $this->applySelect($select);
        }

        // If the client wants exclude specific columns
        if ($select = $inputs[self::EXCLUDE] ?? false) {
            $this->applyExclude($select);
        }

        // Ordering result by a sort string
        $this->applyOrder($inputs[self::SORT] ?? null);

        return $this->builder;
    }

    /**
     * Executes the query builder to get data from database.
     * It will look for a 'with' input in request body. If exits, it will eager load relations
     * If a 'per_page' input is detected, it will be paginated.
     *
     * @param Builder $builder
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection|\Illuminate\Support\Collection
     * @throws FilterException
     */
    public function getOrPaginate(Builder $builder, ?int $perPage = self::PAGE_SIZE)
    {
        $this->builder = $this->builder ?? $builder;

        $result = collect();

        try {
            if ($this->inputs[self::DONT_PAGINATE] ?? false) {
                $result = $this->builder->limit(Controller::MAX_REGISTERS)->get();
            } else {
                $result = $this->builder->paginate($this->inputs[self::PER_PAGE] ?? $perPage);
            }
        } catch (Throwable $throwable) {
            if ($throwable instanceof RelationNotFoundException) {
                preg_match('/\[(.+?)]/', $throwable->getMessage(), $match);
                $this->errors['with'] = trans('exceptions.no_relation', ['data' => $match[1] ?? '']);
            } else {
                $this->errors['error'] = trans('exceptions.server_error');
                $this->debug[] = [
                    'filter_error' => $throwable->getMessage(),
                ];
            }
        }

        if (count($this->errors)) {
            throw new FilterException($this->errors, $this->debug);
        }

        return $result;
    }

    /**
     * Useful when using a nullable timestamp
     *
     * @param string $attribute
     * @param boolean $value
     * @param Builder|null $builder
     * @return Builder
     */
    protected function filterNullable($attribute, $value, Builder $builder = null): Builder
    {
        $builder = $builder ?? $this->builder;
        return $value
            ? $builder->whereNotNull($attribute)
            : $builder->whereNull($attribute);
    }

    /**
     * Checks if column has any value. It considers falsy values: false, 0, '' and null
     *
     * @param string $attribute
     * @param boolean $value
     * @param Builder|null $builder
     * @return Builder
     */
    protected function existence($attribute, $value, Builder $builder = null): Builder
    {
        $builder = $builder ?? $this->builder;

        return $value
            ? $builder->where(fn (Builder $q) => $q->where($attribute, '!=', '')->where($attribute, '!=', 0)->whereNotNull($attribute))
            : $builder->where(fn (Builder $q) => $q->whereRaw("$attribute regexp '^$|0'")->orWhereNull($attribute));
    }

    /**
     * @param $columns
     * @param Builder|null $builder
     */
    protected function filled($columns, Builder $builder = null)
    {
        $builder = $builder ?? $this->builder;

        collect($columns)
            ->each(fn (string $column) => $this->existence($column, true, $builder));
    }

    /**
     * @param $columns
     * @param Builder|null $builder
     */
    protected function notFilled($columns, Builder $builder = null)
    {
        $builder = $builder ?? $this->builder;

        collect($columns)
            ->each(fn (string $column) => $this->existence($column, false, $builder));
    }

    /**
     * @param $attribute
     * @param $value
     * @param Builder|null $builder
     * @param bool $strict
     * @return Builder
     */
    protected function jsonBool($attribute, $value, Builder $builder = null, ?bool $strict = false): Builder
    {
        $builder = $builder ?? $this->builder;

        if (app()->environment('testing')) { // TODO: temporal solution. In SQLite booleans are saved as int when inside json fields
            $value = (int)$value;
        } else {
            $value = (bool)$value;
        }

        if ($value || $strict) { // Truly value
            return $builder->where($attribute, $value);
        } else { // Falsy value
            return $builder->where(
                fn (Builder $q) => $q
                ->whereNull($attribute)
                ->orWhere($attribute, $value)
            );
        }
    }

    /**
     * @param $ids
     * @param Builder|null $builder
     * @return Builder
     */
    protected function ids($ids, ?Builder $builder = null): Builder
    {
        $builder = $builder ?? $this->builder;

        return $this->filterMultiple('id', $ids, $builder);
    }

    /**
     * @param string $column
     * @param $values
     * @param Builder|null $builder
     * @return Builder
     */
    protected function filterMultiple(string $column, $values, ?Builder $builder = null): Builder
    {
        $builder = $builder ?? $this->builder;

        if (is_string($values) && str_contains($values, ',')) {
            $values = explode(',', $values);
        }

        if (! is_array($values)) {
            $values = [$values];
        }

        return $builder->whereIn($column, $values);
    }

    /**
     * @param $id
     *
     * @return Builder
     */
    protected function id($id): Builder
    {
        return $this->builder->where('id', $id);
    }

    /**
     * @param $value
     *
     * @return Builder
     */
    protected function deleted($value): Builder
    {
        try {
            $this->builder->withTrashed();
        } catch (Exception $exception) {
        }

        return $this->filterNullable('deleted_at', $value);
    }

    /**
     * It returns an array containing all key-values based on $filter property
     * Every 'key' ($filter) MUST MATCH with the correct method name of the FilterModel class.
     * @param array $inputs
     * @return void
     */
    private function applyFilters(array $inputs): void
    {
        collect($inputs)
            ->filter(fn ($value) => ! is_null($value) && '' !== $value) // removes all NULL and Empty Strings but leaves 0 (zero) and false values
            ->mapWithKeys(fn ($value, $input) => [Str::camel($input) => $value]) // convert inputs to camelCase
            ->filter(fn ($value, $filter) => method_exists($this, $filter))
            ->each(fn ($value, $filter) => $this->execute($filter, $value));
    }

    /**
     * @param array $inputs
     * @return void
     */
    private function applyRanges(array $inputs = []): void
    {
        collect(array_keys($inputs))
            ->filter(fn (string $input) => Str::startsWith($input, [self::MIN_PREFIX, self::MAX_PREFIX]) && ! method_exists($this, Str::camel($input)))
            ->map(fn (string $input) => $this->applyRange($input, $inputs[$input]));
    }

    /**
     * @param string $input
     * @param $value
     */
    private function applyRange(string $input, $value)
    {
        if (is_null($value)) {
            return;
        }

        // Clean up prefixes to determine the table's column name
        $column = str_replace(self::MIN_PREFIX, '', str_replace(self::MAX_PREFIX, '', $input));

        $operator = Str::startsWith($input, self::MIN_PREFIX)
            ? '>='
            : '<=';

        if (isDateString($value)) {
            try {
                if($this->validateDateFormat($value)){
                    $date = Carbon::parse($value);
                    $dateString = $operator === '>='
                     ? $date
                     : $date;
                     
                    $this->builder->where($column, $operator, $dateString);
                } else{
                    $date = Carbon::parse($value);
                    $dateString = $operator === '>='
                     ? $date->startOfDay()->toDateTimeString()
                     : $date->endOfDay()->toDateTimeString();
                    
                    $this->builder->where($column, $operator, $dateString);
                }
            } catch (Exception $exception) {
                $this->errors[$input] = trans('exceptions.wrong_format', ['value' => $value]);
            }
        } else {
            $this->builder->where($column, $operator, (float)$value);
        }
    }
    /**
     * @param string $input
     * @param $value
     */
    private function validateDateFormat($date, $format = 'Y-m-d H:i:s')
    {   
        try{
            $dateWithFormat = Carbon::createFromFormat($format, $date);
            return $dateWithFormat->format($format) == $date;
        }catch(Exception $exception) {
            return false;
        }
    }

    /**
     * Orders by a 'sort' string if it's present in request
     * This sort string must be with the format 'attribute|orderDirection'
     * for example: a string 'name|asc' means: ordering by 'name' in 'ascending' order.
     * By default, it will order by created_at
     *
     *
     * @param string|null $sortString
     * @return Builder
     */
    private function applyOrder(?string $sortString = null)
    {
        if ($sortString === 'none') {
            return $this->builder;
        }
        if ($sortString && str_contains($sortString, '|')) {
            $this->builder->getQuery()->orders = []; // clean up any previous ordering

            list($attribute, $direction) = explode('|', $sortString);

            return $this->builder->orderBy($attribute, $direction);
        } else {
            return $this->builder->orderBy('id', 'desc'); // default ordering
        }
    }

    /**
     * The $relations param can be an array or a string, specifying relations that must be eager loaded
     * The value of $relations will be changed from under_score_case to camelCase.
     *
     * @param array|string $with
     */
    private function applyRelations($with)
    {
        $relations = collect($with)
            ->mapWithKeys(fn ($relation, $key) => is_numeric($key) ? [$key => Str::camel($relation)] : [Str::camel($key) => $relation])
            ->toArray();

        $this->builder->with($relations);
    }

    /**
     * Add whereBetween eloquent queries.
     * It must be an Array. Array $range must contain 3 items: [attribute, from, to]
     * Where:
     *   -> attribute is a string with the name of the model attribute
     *   -> from is the initial value of between query
     *   -> to in the ending value of between query.
     *
     * @param array $range
     *
     * @return void
     */
    private function applyWhereBetween($range): void
    {
        if (is_array($range) && 3 === count($range)) {
            $this->builder->whereBetween($range[0], [$range[1], $range[2]]);
        } else {
            info("Can't apply between filter. Wrong array");
        }
    }

    /**
     * @param string|array $relations
     * @return void
     */
    private function applyDoesntHave($relations): void
    {
        collect($relations)
            ->map(fn ($name) => Str::camel($name))
            ->each(fn ($name) => $this->builder->whereDoesntHave($name));
    }

    /**
     * @param string|array $relation
     * @return void
     */
    private function applyHaving($relation): void
    {
        collect($relation)
            ->map(fn ($name) => Str::camel($name))
            ->each(fn ($name) => $this->builder->whereHas($name));
    }

    /**
     * @return void
     */
    private function applyOnlyTrashed()
    {
        try {
            $this->builder->onlyTrashed();
        } catch (Exception $exception) {
        }
    }

    /**
     * Add Eloquent relationship counts. $relations can be an array or a simple string.
     * It will add a {relation}_count attribute to returned Models
     *
     * @param $relations
     * @return void
     */
    private function applyRelationCounts($relations): void
    {
        $relations = collect($relations)
            ->map(fn ($relation) => Str::camel($relation))
            ->toArray();

        $this->builder->withCount($relations);
    }

    /**
     * @param $columns
     * @return void
     */
    private function applySelect($columns): void
    {
        $column = collect($columns)
            ->map(function ($column) {
                if (str_contains($column, ' as ')) {
                    return $column;
                }
                return Str::snake($column);
            })
            ->toArray();

        $this->builder->select($column);
    }

    /**
     * @param $columns
     * @return void
     */
    private function applyExclude($columns): void
    {
        $columns = collect($columns)
            ->map(fn ($column) => Str::snake($column))
            ->toArray();

        $this->builder->select(array_diff($this->getTableColumns(), $columns));
    }

    /**
     * @return array
     */
    private function getTableColumns(): array
    {
        return $this->builder
            ->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->builder->getModel()->getTable());
    }

    /**
     * @param string $filter
     * @param $value
     */
    private function execute(string $filter, $value)
    {
        try {
            $this->$filter($value);
        } catch (Throwable $throwable) {
            $this->errors[] = [$filter => $value];
            $this->debug[] = $throwable->getMessage();
        }
    }
}
