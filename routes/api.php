<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RecordController;
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
Route::prefix('/patients')->middleware('auth:sanctum')->group(function (){
    Route::get('/{patient}/records' , [PatientController::class , 'patientRecords']);
    Route::get('/{patient}/reservations' , [PatientController::class , 'patientReservations']);
    Route::get('/{patient}/reservations/count' , [PatientController::class , 'patientReservationsCount']);
    Route::post('/{patient}/profile-image' , [PatientController::class , 'addProfileImage']);
    Route::delete('/{patient}/profile-image' , [PatientController::class , 'deleteProfileImage']);
    Route::post('/{patient}/file' , [PatientController::class , 'addFile']);
});

Route::apiResource('/reservations' , ReservationController::class)->middleware('auth:sanctum');
Route::patch('/reservations/{reservation}/change-status' , [ReservationController::class , 'changeStatus'])->middleware('auth:sanctum');

Route::apiResource('records' , RecordController::class)->except(['index']);

Route::prefix('overviews')->middleware('auth:sanctum')->group(function (){
    Route::get('/patients/gender/count' , [OverviewController::class , 'patientsGenderCount']);
    Route::get('/ills/count' , [OverviewController::class , 'illsCount']);
    Route::get('/records/count' , [OverviewController::class , 'recordsCount']);
});

Route::prefix('/file-manager')->middleware('auth:sanctum')->group(function (){
    Route::get('' , [FileManagerController::class , 'index']);
    Route::post('' , [FileManagerController::class , 'store']);
    Route::delete('/' , [FileManagerController::class , 'delete']);
    Route::get('/{media}/download' , [FileManagerController::class , 'download']);
    Route::get('/medical-collections' , [FileManagerController::class , 'getMedicalCollections']);
});
