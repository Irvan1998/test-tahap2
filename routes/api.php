<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
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


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::controller(KendaraanController::class)->middleware('jwt.verify')->prefix('kendaraan')->group(function () {
    Route::post('save', 'store');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
