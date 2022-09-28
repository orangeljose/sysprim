<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use Illuminate\Database\Eloquent\Builder;
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
    public function index()
    {
        $vehicles = Vehicle::filter(request()->all())
        ->whereHas('vehicleModel', fn (Builder $q) => 
            $q->whereHas('vehicleBrand', fn (Builder $q)=> 
                $q->whereNull(['deleted_at']))
        )
        ->get();            

        return view('Vehicles.index', compact('vehicles'));
    }

    /**
     * Show the vehicle creation view.
     *
     * @return \Illuminate\View\View
     */
    public function create(){
        $vehicleBrands = VehicleBrand::filter(request()->all())->get();

        $vehicleModels = VehicleModel::filter(request()->all())
        ->whereHas('vehicleBrand', fn (Builder $q) => $q->whereNull(['deleted_at']))
        ->get();

        return view('Vehicles.create', compact('vehicleBrands','vehicleModels'));
    }
    
    /**
     * Show the vehicle edition view.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id){
        $vehicle = Vehicle::where('id', '=', $id)->get()->first();

        $vehicleModels = VehicleModel::filter(request()->all())
        ->whereHas('vehicleBrand', fn (Builder $q) => $q->whereNull(['deleted_at']))
        ->get();
        
        $vehicleBrands = VehicleBrand::filter(request()->all())->get();
        
        return view('Vehicles.edit', compact('vehicle','vehicleModels','vehicleBrands'));        
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
