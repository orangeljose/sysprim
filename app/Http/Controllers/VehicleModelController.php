<?php

namespace App\Http\Controllers;

use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\VehicleModelRequest;
use App\Http\Resources\VehicleModelResource;
use App\Http\Requests\Storevehicle_modelRequest;
use App\Http\Requests\Updatevehicle_modelRequest;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of Vehicle Brands.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $vehicleModels = VehicleModel::filter(request()->all())
        ->getOrPaginate();

        return $this->indexResponse(VehicleModelResource::collection($vehicleModels));
    }

    /**
     * Store a newly created VehicleBrand in storage.
     *
     * @param VehicleModelRequest $request
     *
     * @return JsonResponse
     */
    public function store(VehicleModelRequest $request)
    {
        $vehicleModel = VehicleModel::create($request->validated());

        return $this->storeResponse(new VehicleModelResource($vehicleModel));
    }

    /**
     * Display the specified VehicleModels.
     *
     * @param VehicleModel $vehicleModel
     *
     * @return JsonResponse
     */
    public function show(VehicleModel $vehicleModel)
    {
        return $this->showResponse(new vehicleModelResource($vehicleModel));
    }

    /**
     * Update the specified VehicleModel in storage.
     *
     * @param VehicleModelRequest $request
     * @param VehicleModel $vehicleModel
     *
     * @return JsonResponse
     */
    public function update(VehicleModelRequest $request, VehicleModel $vehicleModel)
    {
        $vehicleModel->update($request->validated());

        return $this->updateResponse(new VehicleModelResource($vehicleModel));
    }

    /**
     * Remove the specified VehicleModel from storage.
     *
     * @param VehicleModel $vehicleModel
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(VehicleModel $vehicleModel)
    {
        $this->destroyModel($vehicleModel);

        return $this->destroyResponse();
    }
}
