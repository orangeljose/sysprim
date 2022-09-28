<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\VehicleModelRequest;
use App\Http\Resources\VehicleModelResource;
use App\Http\Requests\Storevehicle_modelRequest;
use App\Http\Requests\Updatevehicle_modelRequest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        ->whereHas('vehicleBrand', fn (Builder $q) => $q->whereNull(['deleted_at']))
        ->get();

        return view('VehicleModels.index', compact('vehicleModels'));
    }

    /**
     * Show the brand creation view.
     *
     * @return \Illuminate\View\View
     */
    public function create(){
        $vehicleBrands = VehicleBrand::filter(request()->all())->get();

        return view('VehicleModels.create', compact('vehicleBrands'));
    }

    /**
     * Show the Vehicle model edition view.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id){
        $model = VehicleModel::where('id', '=', $id)->get()->first();
        $vehicleBrands = VehicleBrand::filter(request()->all())->get();

        return view('VehicleModels.edit', compact('model', 'vehicleBrands'));        
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
    
     /**
     * Display the specified Models asociated to Brand.
     *
     * @param VehicleModel $vehicleModel
     *
     * @return JsonResponse
     */
    public function list($id){

        $filters = ['vehicleBrandId' => $id];

        $vehicleModelList = VehicleModel::filter($filters)
                            ->whereHas('vehicleBrand', fn (Builder $q) => $q->whereNull(['deleted_at']))->get();

        $modelsNotFound[] = ['id'=> 'null', 'name' => 'esta marca no tiene modelos asociados'];

        count($vehicleModelList) === 0 ? $vehicleModelList = $modelsNotFound : $vehicleModelList;

        return $vehicleModelList;
        
    }
}
