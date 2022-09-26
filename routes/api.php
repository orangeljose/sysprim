<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\VehicleModelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
* Vehicles
*/
Route::resource('vehicles', VehicleController::class)->except(['edit', 'create']);
Route::resource('vehicle_brands', VehicleBrandController::class)->except(['edit', 'create']);
Route::resource('vehicle_models', VehicleModelController::class)->except(['edit', 'create']);
//->middleware(['auth'])
