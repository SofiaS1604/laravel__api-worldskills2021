<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/airport', [\App\Http\Controllers\AirportsController::class, 'index']);
Route::post('/register', [\App\Http\Controllers\UsersController::class, 'create']);
Route::post('/login', [\App\Http\Controllers\UsersController::class, 'auth']);
Route::get('/user', [\App\Http\Controllers\UsersController::class, 'index']);
Route::get('/flight', [\App\Http\Controllers\FlightsController::class, 'index']);
Route::post('/booking', [\App\Http\Controllers\BookingsController::class, 'create']);
Route::get('/booking/{code}', [\App\Http\Controllers\BookingsController::class, 'index']);
Route::get('/booking', [\App\Http\Controllers\UsersController::class, 'show']);
Route::get('/booking/{code}/seat', [\App\Http\Controllers\PassengersController::class, 'index']);
Route::patch('/booking/{code}/seat', [\App\Http\Controllers\PassengersController::class, 'update']);

