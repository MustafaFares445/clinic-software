<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function (){
    Route::post('/login' , [AuthController::class , 'login']);
    Route::post('/register' , [AuthController::class , 'register']);
    Route::post('/logout' , [AuthController::class , 'logout'])->middleware('auth:api');
    Route::post('/refresh' , [AuthController::class , 'refresh'])->middleware('auth:api');
    Route::post('/send-code' , [AuthController::class , 'sendCode']);
    Route::post('/check-code' , [AuthController::class , 'checkCode']);
});
