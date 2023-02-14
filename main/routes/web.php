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

// charging  station routes
Route::get('/Charging-Station/index', [\App\Http\Controllers\ChargingStationController::class, 'index'])
    ->name('chargingStation.index');
Route::get('/Charging-Station/create', [\App\Http\Controllers\ChargingStationController::class, 'create'])
    ->name('chargingStation.create');
Route::post('/Charging-Station/store', [\App\Http\Controllers\ChargingStationController::class, 'store'])
    ->name('chargingStation.store');

//rating routes
Route::get('/rate', [\App\Http\Controllers\RatingController::class, 'provideRating'])
    ->name('rating.provide');
Route::post('/addRating', [\App\Http\Controllers\RatingController::class, 'addRating'])
    ->name('rating.add');

// ajax routes
Route::post('/district/getDistricts', [\App\Http\Controllers\DistrictController::class, 'getDistricts']);
Route::post('/metropolitan/getMetropolitans', [\App\Http\Controllers\MetropolitanController::class, 'getMetropolitans']);
Route::post('/metropolitan/getWards', [\App\Http\Controllers\MetropolitanController::class, 'getWards']);
Route::post('/chargingStation/getChargingStations', [\App\Http\Controllers\ChargingStationController::class, 'getChargingStations']);


