<?php

namespace App\Http\Controllers;

use App\Models\VehicleModel;
use App\Http\Requests\Storevehicle_modelRequest;
use App\Http\Requests\Updatevehicle_modelRequest;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storevehicle_modelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storevehicle_modelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function show(vehicle_model $vehicle_model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function edit(vehicle_model $vehicle_model)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatevehicle_modelRequest  $request
     * @param  \App\Models\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function update(Updatevehicle_modelRequest $request, vehicle_model $vehicle_model)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehicle_model $vehicle_model)
    {
        //
    }
}
