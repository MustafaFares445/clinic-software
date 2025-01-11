<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function (){
    Route::post('/login' , [AuthController::class ,'login']);
    Route::post('/register' , [AuthController::class ,'register']);
    Route::post('/logout' , [AuthController::class ,'logout'])->middleware('auth:sanctum');
});

Route::apiResource('/patients' , PatientController::class)->middleware('auth:sanctum');
Route::apiResource('/reservations' , ReservationController::class)->middleware('auth:sanctum');
Route::patch('/reservations/{reservation}/change-status' , [ReservationController::class , 'changeStatus'])->middleware('auth:sanctum');
