<?php

use \App\Http\Controllers\ChargingStationController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MetropolitanController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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



Auth::routes();


// charging  station routes
Route::get('/Charging-Station/index', [ChargingStationController::class, 'index'])
    ->name('chargingStation.index');
Route::get('/Charging-Station/create', [ChargingStationController::class, 'create'])
    ->name('chargingStation.create');
Route::post('/Charging-Station/store', [ChargingStationController::class, 'store'])
    ->name('chargingStation.store');

//rating routes
Route::get('/ratings/index', [RatingController::class, 'index'])
    ->name('ratings.index');
Route::get('/rate', [RatingController::class, 'provideRating'])
    ->name('rating.provide');
Route::post('/addRating', [RatingController::class, 'addRating'])
    ->name('rating.add');
Route::post('/editRating', [RatingController::class, 'editRating'])
    ->name('rating.edit');

//recommendation routes
Route::get('/recommendations', [RecommendationController::class, 'index'])
    ->name('recommendations.index');
Route::post('/recommendations', [RecommendationController::class, 'getRecommendation'])
    ->name('getRecommendation');

// ajax routes
Route::post('/district/getDistricts', [DistrictController::class, 'getDistricts']);
Route::post('/metropolitan/getMetropolitans', [MetropolitanController::class, 'getMetropolitans']);
Route::post('/metropolitan/getWards', [MetropolitanController::class, 'getWards']);
Route::post('/chargingStation/getChargingStations', [ChargingStationController::class, 'getChargingStations']);


// default routes
Route::get('/', function () {
    return  redirect()->route('login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
