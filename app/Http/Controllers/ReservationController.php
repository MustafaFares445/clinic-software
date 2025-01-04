<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatuses;
use App\Http\Requests\ReservationIndexRequest;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Clinic;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Display a listing of reservations",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="start",
     *         in="query",
     *         description="Start date filter",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end",
     *         in="query",
     *         description="End date filter",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ReservationResource"))
     *     )
     * )
     */
    /**
     * Handles the retrieval of reservations based on the provided request parameters.
     *
     * @param ReservationIndexRequest $request The incoming request containing filters for reservations.
     * @return AnonymousResourceCollection|JsonResponse A collection of reservations or a JSON response with an error.
     */
    public function index(ReservationIndexRequest $request): AnonymousResourceCollection|JsonResponse
    {
        // Validate clinic access for the authenticated user
        if ($request->validated('clinicId') && Auth::user()->doctorClinics()->where('clinic_id', $request->validated('clinicId'))->doesntExist()) {
            return response()->json(['error' => 'You are not allowed.'], ResponseAlias::HTTP_FORBIDDEN);
        }

        // Determine the clinic ID to use
        $clinicId = $request->validated('clinicId') ?? Auth::user()->clinic_id;
        $clinic = Clinic::query()->select(['start', 'end'])->find($clinicId);

        // Update reservation statuses from 'income' to 'check' if they have ended
        Reservation::query()
            ->where('status', ReservationStatuses::INCOME)
            ->whereDate('end', '<', now())
            ->update(['status' => ReservationStatuses::CHECK]);

        // Determine the start and end dates for the query
        $startDate = $request->validated('start') ?? now()->startOfWeek(CarbonInterface::SATURDAY);
        $endDate = $request->validated('end') ?? now()->endOfWeek(CarbonInterface::FRIDAY);

        // Adjust start and end dates based on clinic working hours
        if ($clinic->start && $clinic->end) {
            $startDate = $startDate->setTimeFromTimeString($clinic->start);
            $endDate = $endDate->setTimeFromTimeString($clinic->end);
        } else {
            $startDate = $startDate->startOfDay();
            $endDate = $endDate->endOfDay();
        }

        // Build the reservation query with necessary filters and sorting
        $reservationQuery = Reservation::query()
            ->with(['patient' => function ($query) {
                $query->select(['id' , 'firstName' , 'lastName']);
            }])
            ->with(['doctor' => function ($query) {
                $query->select(['id' , 'fullName']);
            }])
            ->with(['specification' => function ($query) {
                $query->select(['id' , 'name']);
            }])
            ->whereDate('start', '>=', $startDate)
            ->whereDate('end', '<=', $endDate)
            ->when(Auth::user()->hasExactRoles('doctor'), function (Builder $query) {
                $query->where('doctor_id', Auth::id());
            })
            ->orderBy('start');

        // Return the collection of reservations
        return ReservationResource::collection($reservationQuery->get());
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Store a newly created reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resource created",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function store(ReservationRequest $request): ReservationResource
    {
        $data = $request->validated();
        if (Auth::user()->hasExactRoles('doctor'))
            $data['doctor_id'] = Auth::id();

        $reservation = Reservation::query()->create($data);

        return ReservationResource::make($reservation->load(['patient' , 'doctor' , 'specification']));
    }


    /**
     * @OA\Get(
     *     path="/api/reservations/{reservation}",
     *     summary="Display the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function show(Reservation $reservation): AnonymousResourceCollection
    {
        return ReservationResource::collection($reservation->load(['patient' , 'doctor' , 'specification']));
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/{reservation}",
     *     summary="Update the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resource updated",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function update(ReservationRequest $request, Reservation $reservation): ReservationResource
    {
        $data = $request->validated();
        if (Auth::user()->hasExactRoles('doctor'))
            $data['doctor_id'] = Auth::id();

        $reservation->update($data);

        return ReservationResource::make($reservation->load(['patient' , 'doctor' , 'specification']));
    }

    /**
     * @OA\Patch(
     *     path="/api/reservations/{reservation}/change-status",
     *     summary="Change the status of the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", enum={"income", "check", "dismiss", "cancelled"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status changed",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function changeStatus(Request $request, Reservation $reservation): ReservationResource
    {
        $request->validate([
            'status' => ['required' , 'string' , Rule::in(ReservationStatuses::values())]
        ]);

        $reservation->update(['status' => $request->input('status')]);

        return ReservationResource::make($reservation->load(['patient' , 'doctor' , 'specification']));
    }

    /**
     * @OA\Delete(
     *     path="/api/reservations/{reservation}",
     *     summary="Remove the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     )
     * )
     */
    public function destroy(Reservation $reservation): Response
    {
        $reservation->delete();

        return response()->noContent();
    }
}
