<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function (){
    Route::post('/login' , [\App\Http\Controllers\AuthController::class ,'login']);
    Route::post('/register' , [\App\Http\Controllers\AuthController::class ,'register']);
    Route::post('/logout' , [\App\Http\Controllers\AuthController::class ,'logout'])->middleware('auth:sanctum');
});


Route::apiResource('/reservations' , ReservationController::class)->middleware('auth:sanctum');
Route::patch('/reservation/{reservation}/change-status' , [ReservationController::class , 'changeStatus'])->middleware('auth:sanctum');
