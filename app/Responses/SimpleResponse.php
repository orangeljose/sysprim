<?php

namespace App\Responses;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class SimpleResponse
{
    /**
     * Builds up app's json response structure
     *
     * @param string $message
     * @param null $data
     * @param int $code
     * @return array
     */
    public function json(string $message, $data = null, int $code = Response::HTTP_OK): array
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
    }
    /**
     * Returns a json response that can includes specific data.
     *
     * @param int $code
     * @param $data
     * @param string $message
     *
     * @return JsonResponse
     */
    public function jsonData(string $message, $data = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $jsonData = $this->json($message, $data, $code);

        return response()->json($jsonData, $code);
    }

    /**
     * Builds up a json response using an Api Resource.
     *
     * @param string $message
     * @param JsonResource $resource
     *
     * @param int $code
     * @return JsonResponse
     */
    public function resource(string $message, JsonResource $resource, int $code): JsonResponse
    {
        return $resource->additional(array_merge(compact('code', 'message'), $resource->additional))
            ->response()
            ->setStatusCode($code);
    }

    /**
     * @return JsonResponse
     */
    public function empty(): JsonResponse
    {
        return $this->jsonData(trans('help.no_content'))
            ->setStatusCode(Response::HTTP_NO_CONTENT, trans('help.no_content'));
    }

    /**
     * @param Exception|string $exception
     * @param int $code
     * @param array|null $data
     * @return JsonResponse
     */
    public function exception(?Exception $exception, int $code = Response::HTTP_FORBIDDEN, ?array $data = []): JsonResponse
    {
        if ($exception instanceof Exception) {
            $message = $exception->getMessage();
            if ($message === 'This action is unauthorized.') {
                $message = trans('exceptions.unauthorized');
            }
        } elseif (Lang::has("exceptions.$exception")) {
            $message = trans("exceptions.$exception");
        } else {
            $message = $exception ?? trans('exceptions.server_error');
        }

        return $this->jsonData($message, $data, $code);
    }
}
