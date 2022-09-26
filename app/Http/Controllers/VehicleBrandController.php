<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\VehicleBrandRequest;
use App\Http\Resources\VehicleBrandResource;
use App\Http\Requests\Storevehicle_brandRequest;
use App\Http\Requests\Updatevehicle_brandRequest;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of Vehicle Brands.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $vehicleBrands = VehicleBrand::filter(request()->all())
        ->getOrPaginate();

        return $this->indexResponse(VehicleBrandResource::collection($vehicleBrands));
    }

    /**
     * Store a newly created VehicleBrand in storage.
     *
     * @param VehicleBrandRequest $request
     *
     * @return JsonResponse
     */
    public function store(VehicleBrandRequest $request)
    {
        $vehicleBrand = VehicleBrand::create($request->validated());

        return $this->storeResponse(new VehicleBrandResource($vehicleBrand));
    }

    /**
     * Display the specified VehicleBrands.
     *
     * @param VehicleBrand $vehicleBrand
     *
     * @return JsonResponse
     */
    public function show(VehicleBrand $vehicleBrand)
    {
        return $this->showResponse(new VehicleBrandResource($vehicleBrand));
    }

    /**
     * Update the specified VehicleBrand in storage.
     *
     * @param VehicleBrandRequest $request
     * @param VehicleBrand $vehicleBrand
     * @param FileService $fileService
     *
     * @return JsonResponse
     */
    public function update(VehicleBrandRequest $request, VehicleBrand $vehicleBrand)
    {
        $vehicleBrand->update($request->validated());

        return $this->updateResponse(new VehicleBrandResource($vehicleBrand));
    }

    /**
     * Remove the specified VehicleBrand from storage.
     *
     * @param VehicleBrand $vehicleBrand
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(VehicleBrand $vehicleBrand)
    {
        $this->destroyModel($vehicleBrand);

        return $this->destroyResponse();
    }
}
