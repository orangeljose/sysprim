<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Responses\SimpleResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected string $modelName;
    protected bool $female = false;
    protected bool $onlyAction = false;
    private SimpleResponse $simpleResponse;


    public function __construct()
    {
        $this->simpleResponse = resolve(SimpleResponse::class);

    }

    /**
     * Basic show response with a single Resource.
     *
     * @param JsonResource $resource
     *
     * @param string $verb
     * @param int $code
     * @return JsonResponse
     */
    public function showResponse( $resource, string $verb = 'showing', int $code = Response::HTTP_OK): JsonResponse
    {
        $this->loadRelations($resource->resource);

        return $this->simpleResponse->resource($this->buildMessage($verb), $resource, $code);
    }

    /**
     * Basic index response with a collection of Resources.
     *
     * @param JsonResource $resource
     *
     * @param string $verb
     * @param int $code
     * @return JsonResponse
     */
    public function indexResponse(JsonResource $resource, string $verb = 'showing', int $code = Response::HTTP_OK): JsonResponse
    {
        /** @var JsonResource|null $itemsResource */
        $itemsResource = $resource->resource->first();
        if ($itemsResource && $itemsResource->resource instanceof IsConstantInterface) {
            $this->loadRelations($itemsResource);
        }

        return $this->simpleResponse->resource($this->buildMessage($verb, true), $resource, $code);
    }

    /**
     * Basic store response with a single Resource.
     *
     * @param JsonResource $resource
     *
     * @param string $verb
     * @param int $code
     * @return JsonResponse
     */
    public function storeResponse(JsonResource $resource, string $verb = 'created', int $code = Response::HTTP_CREATED): JsonResponse
    {
        return $this->simpleResponse->resource($this->buildMessage($verb), $resource, $code);
    }

    /**
     * Basic update response with a single Resource.
     *
     * @param JsonResource $resource
     *
     * @param string $verb
     * @param int $code
     * @return JsonResponse
     */
    public function updateResponse(JsonResource $resource, string $verb = 'updated', int $code = Response::HTTP_ACCEPTED): JsonResponse
    {
        return $this->simpleResponse->resource($this->buildMessage($verb), $resource, $code);
    }

    /**
     * Basic destroy response. A Resource can be specified.
     *
     * @param JsonResource|null $resource
     *
     * @param string $verb
     * @param int $code
     * @return JsonResponse
     */
    public function destroyResponse(JsonResource $resource = null, string $verb = 'deleted', int $code = Response::HTTP_ACCEPTED): JsonResponse
    {
        $message = $this->buildMessage($verb);

        return $resource
            ? $this->simpleResponse->resource($message, $resource, $code)
            : $this->simpleResponse->jsonData($message, null, Response::HTTP_ACCEPTED);
    }

    /**
     * Call delete() method on a given model. If all goes ok, it will return true.
     *
     * @param Model|mixed $model
     * @return Model
     * @throws AuthorizationException
     */
    public function destroyModel(Model $model): Model
    {
        try {
            $model->delete();

            return $model;
        } catch (Exception $e) {
            throw new AuthorizationException(trans('exceptions.could_not_delete'));
        }
    }

    /**
     * Executes 'load()' method on model to get data from relationships.
     * It will look for a 'with' input in request body. $with can be a string or an array of strings.
     *
     * @param Model|Collection|JsonResource $model
     *
     * @return Model
     */
    protected function loadRelations($model)
    {
        if ($with = request()->get('with')) {
            $relations = collect($with)
                ->map(fn ($relation) => Str::camel($relation))
                ->toArray();

            $model->load($relations);
        }

        return $model;
    }

        /**
     * Little helper for controller's messages. It builds up and translate a message
     *
     * @param string $action
     *
     * @param bool $plural
     * @return string
     */
    public function buildMessage(string $action, bool $plural = false): string
    {
        $translatedAction = trans_choice("actions.$action", $this->female ? 2 : 1);

        if ($this->onlyAction) {
            return $translatedAction;
        }

        $modelName = Str::lower($this->getModelName());

        $model = trans_choice("models.$modelName.$modelName", $plural ? 2 : 1);

        if ($action === 'showing') {
            $translatedAction = trans("actions.$action");

            return "$translatedAction $model";
        } else {
            return "$model $translatedAction";
        }
    }

    /**
     * Helper for retrieving Model name.
     * It calculates the model name by the Controller name must keep naming convention (plural) of models controlled.
     * For changing the ModelName manually, modify $modelName property.
     *
     * @return string
     */
    private function getModelName(): string
    {
        // Get the ModelName from Controller's name. Example: DeliveryNoteController -> delivery_note
        return $this->modelName ?? Str::snake(str_replace('Controller', '', class_basename($this)));
    }

}
