<?php



/**
 * Helper that returns an ID of a specified Model (by class name).
 * If there are any Model, it will grab one randomly, if not, it will create a new one using its Factory
 * When using make() it won't write in Database, instead, it will return a 1
 *
 * @param string $modelClass
 * @param array $attributes
 * @param bool $forceNew
 * @return int
 */
function associateTo(string $modelClass, array $attributes = [], ?bool $forceNew = false): int
{
    /** @var Model $model */
    $model = resolve($modelClass);

    $isMaking = collect(debug_backtrace())
        ->filter(fn ($item) => isset($item['function']) && $item['function'] === 'make')
        ->filter(fn ($item) => isset($item['file']) && Str::endsWith($item['file'], 'Associate.php'))
        ->count();

    if ($isMaking) {
        return 1;
    }

    if ($forceNew) {
        return create($modelClass, $attributes)->id;
    }

    $first = $model::query()->where($attributes)->inRandomOrder()->first();

    return $first->id
        ?? create($modelClass, $attributes)->id; // call to Factory
}