<?php

use App\Http\Controllers\Api\MedicalCaseController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ChronicDiseasController;
use App\Http\Controllers\ChronicMedicationController;
use App\Http\Controllers\BillingTransactionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FillingMaterialController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\MedicalSessionController;
use App\Http\Controllers\TreatmentController;

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/clinics/subscription', [ClinicController::class , 'store']);
Route::get('/app/download', [FileManagerController::class, 'downloadApp']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('/user', function (Request $request) {
        return UserResource::make($request->user());
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::post('/feedback' , [FeedbackController::class , 'store']);

    // Clinic routes
    Route::apiResource('/clinics', ClinicController::class)->except(['store']);

    // Patient routes
    Route::apiResource('/patients', PatientController::class);
    Route::prefix('/patients')->group(function () {
        Route::get('/mini', [PatientController::class , 'mini']);
        Route::get('/{patient}/medical/cases', [PatientController::class , 'patientMedicalCases']);
        Route::get('/{patient}/teeth', [PatientController::class , 'getPatientTeeth']);
        Route::get('/{patient}/reservations', [PatientController::class, 'patientReservations']);
        Route::get('/{patient}/reservations/count', [PatientController::class, 'patientReservationsCount']);
        Route::post('/{patient}/profile-image', [PatientController::class, 'addProfileImage']);
        Route::delete('/{patient}/profile-image', [PatientController::class, 'deleteProfileImage']);
        Route::get('/{patient}/files', [PatientController::class, 'getFiles']);
        Route::post('/{patient}/file', [PatientController::class, 'addFile']);
        Route::delete('/{patient}/file/{media}', [PatientController::class, 'deleteFile']);
    });

    // Reservation routes
    Route::apiResource('/reservations', ReservationController::class);
    Route::post('/reservations/check-conflict', [ReservationController::class, 'checkConflict']);

    // Record routes
    Route::apiResource('records', RecordController::class)->except(['index']);
    Route::prefix('/records')->group(function(){
        Route::post('/{record}/files', [RecordController::class, 'addFile']);
        Route::delete('/{record}/files/{file}' , [RecordController::class , 'deleteFile']);
    });


    // Transaction routes
    Route::apiResource('/transactions/billing', BillingTransactionController::class);

    // Overview routes
    Route::prefix('overview')->group(function () {
        Route::get('/patients/gender/count', [OverviewController::class, 'patientsGenderCount']);
        Route::get('/medical/cases/count', [OverviewController::class, 'medicalCasesCount']);
        Route::get('/general-statistics', [OverviewController::class, 'generalStatistics']);
        Route::get('/patients/count', [OverviewController::class, 'patientsCount']);
        Route::get('/billing-statistics', [OverviewController::class, 'getStatistics']);
        Route::get('/age-statistics', [OverviewController::class, 'getAgeStatistics']);
        Route::get('/chart/billing-statistics', [OverviewController::class, 'billingChartStatistics']);
    });


    Route::prefix('/chronics' ,function(){
        Route::apiResource('/medications', ChronicMedicationController::class);
        Route::apiResource('/diseases', ChronicDiseasController::class);
    });

    Route::apiResource('/treatments' , TreatmentController::class);
    Route::apiResource('/laboratories' , LaboratoryController::class);
    Route::apiResource('/filling/materials' , FillingMaterialController::class);
    Route::apiResource('/medical/cases' , MedicalCaseController::class)->except(['index' , 'show']);
    Route::apiResource('/mediclal/seesions' , MedicalSessionController::class)->except(['index' , 'show']);

});
