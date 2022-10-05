<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\VehicleModelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => 'auth'], function() {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route::view('profile','profile')->name('profile');
    // Route::put('profile', [ProfileController::class,'update'])->name('profile.update');
    Route::resource('vehicles', VehicleController::class);
    Route::resource('vehicle_brands', VehicleBrandController::class);
    Route::resource('vehicle_models', VehicleModelController::class);
    Route::get('/vehicle_models/list/{brand}', [VehicleModelController::class, 'list'])->name('vehicle_models.lista');

});

require __DIR__.'/auth.php';
