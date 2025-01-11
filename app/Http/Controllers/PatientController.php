<?php

namespace App\Http\Controllers;

use App\Actions\PatientsOrder;
use App\Http\Requests\PatientIndexRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PatientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="Get a list of patients",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="clinicId",
     *         in="query",
     *         description="Filter patients by clinic ID",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Field to order patients by",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"firstName", "lastName", "nextReservation", "lastReservation", "createdAt"},
     *             nullable=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="orderType",
     *         in="query",
     *         description="Order type",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"DESC", "ASC"},
     *             nullable=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of patients per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PatientResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="You are not allowed.")
     *         )
     *     )
     * )
     */
    public function index(PatientIndexRequest $request , PatientsOrder $patientsOrderAction): AnonymousResourceCollection|JsonResponse
    {
        // Validate clinic access for the authenticated user
        if ($request->has('clinicId') && Auth::user()->doctorClinics()->where('clinic_id', $request->validated('clinicId'))->doesntExist()) {
            return response()->json(['error' => 'You are not allowed.'], ResponseAlias::HTTP_FORBIDDEN);
        }

        $patientsQuery = Patient::with('media')
            ->when($request->has('clinicId') , function (Builder $query){
                $query->where('clinic_id' , request()->input('clinicId'));
            })
            ->when(request()->has('clinicId') && Auth::user()->hasRole('doctor'), function (Builder $query) {
                $query->whereHas('reservations' , function (Builder $query){
                    $query->where('doctor_id' , Auth::id());
                });
            })
            ->select([
                'patients.id',
                'patients.firstName',
                'patients.lastName',
                'patients.created_at',
                'next_reservation.start as next_reservation_date',
                'last_reservation.start as last_reservation_date',
                'created_at'
            ])
            ->leftJoinSub(
                Reservation::query()
                    ->selectRaw('patient_id, MIN(start) as start')
                    ->where('start', '>=', now())
                    ->groupBy('patient_id'),
                'next_reservation',
                'next_reservation.patient_id',
                '=',
                'patients.id'
            )
            ->leftJoinSub( // Add sub query for last reservation
                Reservation::query()
                    ->selectRaw('patient_id, MAX(start) as start')
                    ->where('start', '<', now())
                    ->groupBy('patient_id'),
                'last_reservation',
                'last_reservation.patient_id',
                '=',
                'patients.id'
            );

        $patientsOrderAction->order($patientsQuery);

        return PatientResource::collection($patientsQuery->simplePaginate($request->integer('perPage' , 20)));
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Store a newly created patient",
     *     description="Create a new patient record and return the created patient resource",
     *     tags={"Patients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(PatientRequest $request): PatientResource
    {
        $patient = Patient::query()->create(array_merge($request->validated(),[
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return PatientResource::make($patient);
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}",
     *     summary="Get a specific patient",
     *     description="Returns a specific patient resource with related data",
     *     operationId="getPatientById",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="clinicId",
     *         in="query",
     *         required=false,
     *         description="Filter reservations by clinic ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(Patient $patient): PatientResource
    {
        $reservationQuery = $patient->reservations()
            ->when(request()->has('clinicId'), function (Builder $query) {
                $query->where('clinic_id', request()->get('clinicId'));
            })
            ->when(Auth::user()->hasRole('doctor'), function (Builder $query) {
                $query->where('doctor_id', Auth::id());
            });

        $pastReservationsCount = $reservationQuery->clone()->whereDate('start', '<', now())->count();
        $upComingReservationsCount = $reservationQuery->clone()->whereDate('start', '>=', now())->count();

        return PatientResource::make($patient->load(['media', 'records', 'records.reservation']), $pastReservationsCount, $upComingReservationsCount);
    }

    /**
     * @OA\Put(
     *     path="/api/patients/{patient}",
     *     summary="Update a patient",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(PatientRequest $request, Patient $patient): PatientResource
    {
        $patient->update(array_merge($request->validated(),[
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return PatientResource::make($patient);
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{patient}",
     *     summary="Delete a patient",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Patient deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(Patient $patient): Response
    {
        $patient->delete();
        return response()->noContent();
    }
}
