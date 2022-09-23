<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Http\Requests\Storevehicle_brandRequest;
use App\Http\Requests\Updatevehicle_brandRequest;

class VehicleBrandController extends Controller
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
     * @param  \App\Http\Requests\Storevehicle_brandRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storevehicle_brandRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\vehicle_brand  $vehicle_brand
     * @return \Illuminate\Http\Response
     */
    public function show(vehicle_brand $vehicle_brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\vehicle_brand  $vehicle_brand
     * @return \Illuminate\Http\Response
     */
    public function edit(vehicle_brand $vehicle_brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatevehicle_brandRequest  $request
     * @param  \App\Models\vehicle_brand  $vehicle_brand
     * @return \Illuminate\Http\Response
     */
    public function update(Updatevehicle_brandRequest $request, vehicle_brand $vehicle_brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\vehicle_brand  $vehicle_brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(vehicle_brand $vehicle_brand)
    {
        //
    }
}
