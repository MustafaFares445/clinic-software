<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actions\PatientsOrder;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use App\Services\RecordQueryService;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\RecordResource;
use App\Http\Resources\PatientResource;
use App\Http\Requests\PatientIndexRequest;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\PatientRecordsRequest;
use App\Http\Requests\PatientReservationRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\ReservationQueryService;

/**
 * @OA\Tag(
 *     name="Patients",
 *     description="Operations related to Patients"
 * )
 */
final class PatientController extends Controller
{
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaService;
    }

    /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="Get a list of patients",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="clinicId",
     *         in="query",
     *         description="Filter patients by clinic ID",
     *         required=false,
     *
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Field to order patients by",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"firstName", "lastName", "nextReservation", "lastReservation", "registeredAt"},
     *             nullable=true
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="orderType",
     *         in="query",
     *         description="Order type",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"DESC", "ASC"},
     *             nullable=true
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of patients per page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/PatientResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="You are not allowed.")
     *         )
     *     )
     * )
     */
    public function index(PatientIndexRequest $request, PatientsOrder $patientsOrderAction): AnonymousResourceCollection|JsonResponse
    {
        $patientsQuery = Patient::with('media')
            ->select([
                'patients.id',
                'patients.firstName',
                'patients.fatherName',
                'patients.lastName',
                'patients.phone',
                'patients.created_at',
                'next_reservation.start as next_reservation_date',
                'last_reservation.start as last_reservation_date',
                'created_at',
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

        return PatientResource::collection($patientsQuery->paginate($request->integer('perPage', 20)));
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Store a newly created patient",
     *     description="Create a new patient record and return the created patient resource",
     *     tags={"Patients"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
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
        $patient = Patient::query()->create($request->validated());

        return PatientResource::make($patient->load('media'));
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}",
     *     summary="Get a specific patient",
     *     description="Returns a specific patient resource with related data",
     *     operationId="getPatientById",
     *     tags={"Patients"},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to retrieve",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="clinicId",
     *         in="query",
     *         required=false,
     *         description="Filter reservations by clinic ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
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
        return PatientResource::make($patient->load(['permanentIlls', 'permanentMedicines']));
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/records",
     *     summary="Get patient medical records",
     *     description="Retrieves all medical records for a specific patient with filtering options",
     *     operationId="getPatientRecords",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="Patient ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date for filtering records (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date for filtering records (Y-m-d), must be after or equal to startDate",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to filter records by content",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/RecordResource")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 description="Pagination links"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 description="Pagination metadata"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function patientRecords(Patient $patient, PatientRecordsRequest $request): AnonymousResourceCollection
    {
        $records = RecordQueryService::make()
            ->filterByPatient($patient->id)
            ->filterByDateRange($request->validated('startDate'), $request->validated('endDate'))
            ->filterBySearchTerm($request->input('search'))
            ->withRelations([
                'media', 'ills', 'medicines', 'doctors',
                'reservation' => fn($query) => $query->select(['id'  , 'created_at'])
            ])
            ->sortBy('created_at')
            ->getQuery()
            ->cursorPaginate();


        return RecordResource::collection($records);
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/reservations",
     *     summary="Get patient reservations",
     *     description="Retrieves all reservations for a specific patient with filtering and sorting options",
     *     operationId="getPatientReservations",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="Patient ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date for filtering reservations (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date for filtering reservations (Y-m-d), must be after or equal to startDate",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="doctorsIds[]",
     *         in="query",
     *         description="Array of doctor IDs to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="integer")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to filter by patient name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string", enum={"start", "firstName", "lastName"}, default="start")
     *     ),
     *     @OA\Parameter(
     *         name="sortOrder",
     *         in="query",
     *         description="Sort order",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ReservationResource")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 description="Pagination links"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 description="Pagination metadata"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function patientReservations(Patient $patient , PatientReservationRequest $request): AnonymousResourceCollection
    {
        $reservations = ReservationQueryService::make()
            ->filterByPatient($patient->id)
            ->filterByType($request->validated('type'))
            ->filterByDateRange($request->validated('startDate') , $request->input('endDate'))
            ->filterByDoctors($request->validated('doctorsIds'))
            ->filterByPatientName($request->input('search'))
            ->withRelations(['media', 'doctor'])
            ->sortBy($request->validated('sortBy' , 'start'), $request->validated('sortOrder' , 'desc'))
            ->getQuery()
            ->cursorPaginate();

        return ReservationResource::collection($reservations);
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/reservations/count",
     *     summary="Get patient reservations count",
     *     description="Get the count of past and upcoming reservations for a specific patient",
     *     operationId="patientReservationsCount",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="Patient ID",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="clinicId",
     *         in="query",
     *         required=false,
     *         description="Clinic ID to filter reservations",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="pastReservationCount",
     *                 type="integer",
     *                 description="Number of past reservations"
     *             ),
     *             @OA\Property(
     *                 property="upComingReservationsCount",
     *                 type="integer",
     *                 description="Number of upcoming reservations"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function patientReservationsCount(Patient $patient): JsonResponse
    {
        return response()->json([
            'pastReservationCount' => $patient->reservations()->whereDate('start', '<', now())->count(),
            'upComingReservationsCount' => $patient->reservations()->whereDate('start', '>=', now())->count(),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/patients/{patient}",
     *     summary="Update a patient",
     *     tags={"Patients"},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to update",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Patient updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
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
        $patient->update($request->validated());

        return PatientResource::make($patient);
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{patient}",
     *     summary="Delete a patient",
     *     tags={"Patients"},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to delete",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
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

    /**
     * @OA\Post(
     *     path="/api/patients/{patient}/profile-image",
     *     summary="Upload patient profile image",
     *     description="Upload and associate a profile image with a patient",
     *     operationId="addProfileImage",
     *     tags={"Patients"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="Profile image file"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile image uploaded successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function addProfileImage(Patient $patient, ProfileRequest $request): MediaResource
    {
        return MediaResource::make(
            $this->mediaService->handleMediaUpload($patient, $request->file('image'), 'profile')
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{patient}/profile-image",
     *     summary="Delete patient profile image",
     *     description="Remove the profile image associated with a patient",
     *     operationId="deleteProfileImage",
     *     tags={"Patients"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Profile image deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Profile image not found"
     *     )
     * )
     */
    public function deleteProfileImage(Patient $patient): Response
    {
        $patient->getFirstMedia('profile')->delete();

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/files",
     *     summary="get patient files",
     *     tags={"Patients"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *          name="patient",
     *          in="path",
     *          description="Patient ID",
     *          required=true,
     *
     *          @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *         description="Profile image uploaded successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     ),
     * )
     */
    public function getFiles(Patient $patient): AnonymousResourceCollection
    {
        $patient->load('media');
        $profileImage = $patient->getFirstMedia('profile');

        return MediaResource::collection(
            $patient->media()->orderBy('created_at', 'desc')->get()->when($profileImage, function ($collection) use ($profileImage) {
                $collection->reject(function ($media) use ($profileImage) {
                    return $media->id === $profileImage->id;
                });
            })
        );
    }

    /**
     * @OA\Post(
     *     path="/api/patients/{patient}/file",
     *     summary="Upload new file",
     *     tags={"Patients"},
     *     security={{ "bearerAuth": {} }},
     *
     *    @OA\Parameter(
     *          name="patient",
     *          in="path",
     *          description="Patient ID",
     *          required=true,
     *
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                      format="binary",
     *                     description="Files to upload"
     *                 ),
     *                 @OA\Property(
     *                     property="collection",
     *                     type="string",
     *                     description="Collection name for the files"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Files uploaded successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function addFile(Patient $patient, Request $request): MediaResource
    {
        return MediaResource::make(
            $this->mediaService->handleMediaUpload($patient, $request->file('file'), $request->input('collection'))
        );
    }
}
