<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/Charging-Station/create', [\App\Http\Controllers\ChargingStationController::class, 'create'])
    ->name('chargingStation.create');
Route::post('/Charging-Station/store', [\App\Http\Controllers\ChargingStationController::class, 'store'])
    ->name('chargingStation.store');
Route::post('/Charging-Station/getDistricts', [\App\Http\Controllers\ChargingStationController::class, 'getDistricts']);
Route::post('/Charging-Station/getMetropolitans', [\App\Http\Controllers\ChargingStationController::class, 'getMetropolitans']);
Route::post('/Charging-Station/getWards', [\App\Http\Controllers\ChargingStationController::class, 'getWards']);
