<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Http\Requests\StorevehicleRequest;
use App\Http\Requests\UpdatevehicleRequest;
use App\Http\Requests\VehicleResourceRequest;

class VehicleController extends Controller
{
    /**
     * Display a listing of the Vehicles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $vehicles = Vehicle::filter(request()->all())
            ->getOrPaginate();            

        return $this->indexResponse(VehicleResource::collection($vehicles));
    }

    /**
     * Store a newly created Vehicle in storage.
     *
     * @param VehicleRequest $request
     *
     * @return JsonResponse
     */
    public function store(VehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        return $this->storeResponse(new VehicleResource($vehicle));
    }

    /**
     * Display the specified Vehicle.
     *
     * @param Vehicle $vehicle
     *
     * @return JsonResponse
     */
    public function show(Vehicle $vehicle)
    {
        return $this->showResponse(new vehicleResource($vehicle));
    }

    /**
     * Update the specified Vehicle in storage.
     *
     * @param VehicleRequest $request
     * @param Vehicle $vehicle
     *
     * @return JsonResponse
     */
    public function update(VehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return $this->updateResponse(new VehicleResource($vehicle));
    }

    /**
     * Remove the specified Vehicle from storage.
     *
     * @param Vehicle $vehicle
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->destroyModel($vehicle);

        return $this->destroyResponse();
    }
}
