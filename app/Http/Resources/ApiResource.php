<?php

namespace App\Http\Resources;

use App\Interfaces\HasCreatedByInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Str;

class ApiResource extends JsonResource
{
    /**
     * Function to apply when the Resource has a ManyToMany Relationship
     * If the relation is loaded, it returns a MergeValue with two values, one for the Collection and other for the ids.
     * For example, if a User has a manyToMany with Role, you can use:
     *  $this->conditionalRelation('roles')
     * And it will merge:
     *  [
     *      'roles' => RoleResource::collection($this->whenLoaded('roles')),
     *      'role_ids => $this->roles->pluck('id')->toArray(),
     *  ]
     * @param string $relation
     * @param string|null $resourceClass
     * @return MergeValue|MissingValue
     */
    // public function manyToMany(string $relation, string $resourceClass = null)
    // {
    //     if (! $this->relationLoaded($relation)) {
    //         return new MissingValue();
    //     }

    //     $resourceName = $resourceClass
    //         ? class_basename($resourceClass)
    //         : ucfirst(Str::singular($relation)) . 'Resource'; // calculating by convention, roles => RoleResource

    //     $resource = "App\Http\Resources\\$resourceName";

    //     return new MergeValue([
    //         Str::camel($relation) => $resource::collection($this->{$relation}),
    //         Str::snake(Str::singular($relation)) . '_ids' => $this->{$relation}->pluck('id')->toArray(),
    //     ]);
    // }

    // /**
    //  * @param string $pivot
    //  * @param array $attributes
    //  * @return MissingValue|MergeValue
    //  */
    // public function pivotTable(string $pivot, array $attributes)
    // {
    //     if (count($attributes) == 0) {
    //         return new MissingValue();
    //     }
    //     $pivot = Str::snake($pivot);

    //     return $this->whenPivotLoaded($pivot, function () use ($pivot, $attributes) {
    //         return new MergeValue([
    //             $pivot => collect($attributes)
    //                 ->map(function (string $attribute) {
    //                     return [
    //                         'key' => $attribute,
    //                         'value' => $this->pivot->$attribute
    //                     ];
    //                 })->pluck('value', 'key')
    //                 ->toArray()
    //         ]);
    //     });
    // }

    /**
     * It adds counts attributes with the form of {relation}_count applied for n:m relationships
     * If the model has n:m relationship, for example, like $user->roles
     * then it will add a roles_count attribute to the Resource
     *
     * @param string $relation
     * @return MergeValue|MissingValue
     */
    // public function addCount(string $relation)
//     {
//         $attribute = Str::snake($relation) . '_count';

// //        if (! $this->$attribute) {
// //            return new MissingValue();
// //        }

//         return new MergeValue([
//             $attribute => $this->$attribute
//         ]);
//     }

    /**
     * It adds createdBy relationship
     * If the model has n:m relationship, for example, like $user->roles
     * then it will add a roles_count attribute to the Resource
     *
     * @return MergeValue|MissingValue
     */
    // public function addCreatedBy()
    // {
    //     /** @var Model $model */
    //     $model = $this->resource;

    //     if ($model instanceof HasCreatedByInterface) {
    //         return new MergeValue([
    //             'createdBy' => new UserResource($this->whenLoaded('createdBy')),
    //             'created_by' => $model->created_by,
    //         ]);
    //     }

    //     return new MissingValue();
    // }

    // /**
    //  * @param string $relation
    //  * @param string|null $resourceClass
    //  * @return JsonResource|MissingValue
    //  */
    // public function morphedResource(string $relation, string $resourceClass = null)
    // {
    //     $relation = Str::camel($relation); // A bit of normalization

    //     if ($resourceClass) {
    //         return new $resourceClass($this->whenLoaded($relation));
    //     }

    //     $attribute = "${relation}_type";

    //     $morphedClass = class_basename($this->$attribute);

    //     if (! class_exists("App\\Models\\$morphedClass")) {
    //         return new MissingValue();
    //     }

    //     $resource = "App\\Http\\Resources\\${morphedClass}Resource";

    //     return new $resource($this->whenLoaded($relation));
    // }

    /**
     * @param Model|JsonResource|string|null $className
     * @return string
     */
    public function getClassName( $className = null): string
    {
        return Str::of(class_basename($className ?? $this->resource))
            ->lower()
            ->trim()
            ->__toString();
    }
}
