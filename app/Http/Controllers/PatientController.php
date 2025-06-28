<?php

namespace App\Http\Controllers;

use App\Models\Tooth;
use App\Models\Record;
use App\Models\Patient;
use App\DTOs\PatientDTO;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actions\PatientsOrder;
use App\Services\MediaService;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\ToothResource;
use App\Http\Resources\PatientResource;
use App\Services\Filters\PatientFilter;
use App\Http\Requests\PatientListRequest;
use App\Services\ReservationQueryService;
use App\Http\Resources\MedicalCaseResource;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\PatientReservationRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Patients",
 *     description="Operations related to Patients"
 * )
 */
final class PatientController extends Controller
{
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
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Field to order patients by. Options: firstName, lastName, nextReservation, lastReservation, registeredAt",
     *         required=false,
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
     *         description="Order type. Options: DESC, ASC",
     *         required=false,
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
     *         description="Number of patients per page. Default: 20",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PatientResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="You are not allowed.")
     *         )
     *     )
     * )
     */
    public function index(PatientListRequest $request, PatientsOrder $patientsOrderAction): AnonymousResourceCollection|JsonResponse
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
                'address',
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
     * @OA\Get(
     *     path="/api/patients/mini",
     *     summary="Get a mini list of patients",
     *     description="Retrieves a paginated list of patients with minimal information, filtered by the provided criteria. This endpoint is useful for quick lookups or dropdowns.",
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
     *         name="fullName",
     *         in="query",
     *         description="Filter patients by full name",
     *         required=false,
     *         @OA\Schema(type="string", nullable=true)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of patients per page. Default: 20",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PatientResource")
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
     *     )
     * )
     */
    public function mini(PatientListRequest $request)
    {
        $patients = PatientFilter::make(Patient::query())
            ->applyFilters($request->validated())
            ->getQuery()
            ->with('media')
            ->paginate($request->integer('perPage', 20));

        return PatientResource::collection($patients);
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Store a newly created patient",
     *     description="Create a new patient record and return the created patient resource",
     *     tags={"Patients"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid data provided.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
    public function store(PatientRequest $request): PatientResource
    {
        $patient = $this->patientService->createPatient(PatientDTO::fromArray($request->validated()));

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
     *         @OA\Schema(type="string" , format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Patient not found.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(Patient $patient): PatientResource
    {
        return PatientResource::make($patient->load(['media' , 'chronicDiseases' , 'chronicMedications']));
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/medical/cases",
     *     summary="Get patient medical cases",
     *     description="Retrieves all medical cases for a specific patient",
     *     operationId="getPatientMedicalCases",
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
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/MedicalCaseResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     )
     * )
     */
    public function patientMedicalCases(Patient $patient , Request $request)
    {
        $medicalCases = $patient->medicalCases()
        ->when($request->has('toothId') , fn($q) => $q->whereRelation('medicalSessions.records' , 'tooth_id' , $request->get('toothId')))
        ->paginate(request()->get('perPage', 5));

        return MedicalCaseResource::collection($medicalCases);
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/teeth",
     *     summary="Get patient teeth",
     *     description="Retrieves all teeth records for a specific patient, categorized by type (child or adult).",
     *     operationId="getPatientTeeth",
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
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ToothResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     )
     * )
     */
    public function getPatientTeeth(Patient $patient)
    {
        $age = now()->diffInYears($patient->birth);
        $teeth = Tooth::with([
                'records' => fn($q) => $q->where('patient_id'  , $patient->id)->with(['treatment' , 'fillingMaterial'])
            ])
            ->where('type' , $age <= 10 && $age != 0 ? 'child' : 'adult')
            ->orderBy('number')
            ->get();

        return ToothResource::collection($teeth);
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
            ->filterByDateRange($request->validated('startDate') , $request->validated('endDate'))
            ->filterByDoctors($request->validated('doctorsIds'))
            ->filterByPatientName($request->validated('search'))
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
     *         @OA\Schema(type="string" , format="uuid")
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
        $patient = $this->patientService->updatePatient($patient, PatientDTO::fromArray($request->validated()));

        return PatientResource::make(
            $patient->load(['media', 'permanentMedicines', 'permanentIlls'])
        );
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
        $patient->clearMediaCollection();
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
            $this->handleMediaUpload( request: $request, model: $patient, collection:'profile' , name: $request->input('name'))
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
     *     @OA\Parameter(
     *          name="caption",
     *          in="query",
     *          description="Filter by media caption",
     *          required=false,
     *
     *          @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *         description="Files retrieved successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     ),
     * )
     */
    public function getFiles(Patient $patient, Request $request): AnonymousResourceCollection
    {
       $media = Media::query()
            ->where(function($q) use ($patient) {
                $q->whereIn('model_id' , Record::query()->where('patient_id' , $patient->id)->pluck('id')->toArray())
                    ->where('model_type' , Record::class);
            })
            ->orWhere(function($q) use ($patient) {
                $q->where('model_id' , $patient->id)
                    ->where('model_type' , Patient::class);
            })
            ->when($request->has('name') , fn($query) => $query->where('name', 'like', '%' . $request->get('name') . '%'))
            ->get();

        return MediaResource::collection($media);
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
     *                     property="name",
     *                     type="string",
     *                     description="Name of the files"
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
        $media = $this->handleMediaUpload( request: $request, model: $patient, name: $request->input('name'));

        return MediaResource::make($media);
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{patient}/file/{media}",
     *     summary="Delete a patient file",
     *     description="Delete a specific file associated with a patient",
     *     operationId="deletePatientFile",
     *     tags={"Patients"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Parameter(
     *         name="media",
     *         in="path",
     *         description="Media ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="File deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found"
     *     )
     * )
     */
    public function deleteFile(Patient $patient, Media $media)
    {
        $this->handleMediaDelete($patient , $media);

        return response()->noContent();
    }
}
