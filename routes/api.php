<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReservationController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return UserResource::make($request->user());
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('overview')->group(function () {
        Route::get('/patients/gender/count', [OverviewController::class, 'patientsGenderCount']);
        Route::get('/ills/count', [OverviewController::class, 'illsCount']);
        Route::get('/records/count', [OverviewController::class, 'recordsCount']);
        Route::get('/general-statistics', [OverviewController::class, 'generalStatistics']);
        Route::get('/top-ills', [OverviewController::class, 'topIlls']);
        Route::get('/patients/count', [OverviewController::class, 'patientsCount']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::apiResource('/patients', PatientController::class);
    Route::prefix('/patients')->group(function () {
        Route::get('/{patient}/records', [PatientController::class, 'patientRecords']);

        Route::get('/{patient}/reservations', [PatientController::class, 'patientReservations']);
        Route::get('/{patient}/reservations/count', [PatientController::class, 'patientReservationsCount']);

        Route::post('/{patient}/profile-image', [PatientController::class, 'addProfileImage']);
        Route::delete('/{patient}/profile-image', [PatientController::class, 'deleteProfileImage']);
        Route::get('/{patient}/files', [PatientController::class, 'getFiles']);
        Route::post('/{patient}/file', [PatientController::class, 'addFile']);
    });

    Route::apiResource('/reservations', ReservationController::class);

    Route::apiResource('records', RecordController::class)->except(['index']);

    Route::apiResource('/file-manager', FileManagerController::class)->except(['update', 'show']);
    Route::prefix('/file-manager')->group(function () {
        Route::get('/{media}/download', [FileManagerController::class, 'download']);
        Route::get('/medical-collections', [FileManagerController::class, 'getMedicalCollections']);
    });

    Route::apiResource('/clinics', ClinicController::class)->except(['index']);
});
