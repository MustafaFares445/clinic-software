<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reservation;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use App\Enums\ReservationStatuses;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\ReservationIndexRequest;
use App\Http\Requests\CheckReservationConflictRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Reservation",
 *     description="Operations related to reservations"
 * )
 */
final class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Display a listing of reservations",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *
     *     @OA\Parameter(
     *         name="start",
     *         in="query",
     *         description="Start date filter",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="end",
     *         in="query",
     *         description="End date filter",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ReservationResource"))
     *     )
     * )
     */
    /**
     * Handles the retrieval of reservations based on the provided request parameters.
     *
     * @param ReservationIndexRequest $request the incoming request containing filters for reservations
     *
     * @return AnonymousResourceCollection|JsonResponse a collection of reservations or a JSON response with an error
     */
    public function index(ReservationIndexRequest $request): AnonymousResourceCollection|JsonResponse
    {
        // Update reservation statuses from 'income' to 'check' if they have ended
        Reservation::query()
            ->where('status', ReservationStatuses::INCOME)
            ->whereDate('end', '<', now())
            ->update(['status' => ReservationStatuses::DISMISS]);

        // Determine the start and end dates for the query
        $startDate = $request->has('start') ? Carbon::parse($request->validated('start')) : now()->startOfWeek(CarbonInterface::SATURDAY)->startOfDay();
        $endDate = $request->has('end') ? Carbon::parse($request->validated('end')) : now()->endOfWeek(CarbonInterface::FRIDAY)->endOfDay();

        // Build the reservation query with necessary filters and sorting
        $reservationQuery = Reservation::query()
            ->with(['patient' => function ($query) {
                $query->with('media')->select(['id', 'firstName', 'lastName']);
            }]) 
            ->with(['doctor' => function ($query) {
                $query->with('media')->select(['id', 'firstName' , 'lastName']);
            }])
            ->with(['medicalCase' => function ($query) {
                $query->select(['id', 'name' , 'date']);
            }])
            ->whereDate('start', '>=', $startDate)
            ->whereDate('end', '<=', $endDate)
            ->orderBy('start');

        // Return the collection of reservations
        return ReservationResource::collection($reservationQuery->get());
    }


     /**
     * @OA\Post(
     *     path="/api/reservations/check-conflict",
     *     summary="Check for reservation conflicts",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data to check for conflicts",
     *         @OA\JsonContent(ref="#/components/schemas/CheckReservationConflictRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Conflict check result",
     *         @OA\JsonContent(
     *             @OA\Property(property="conflict_exists", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function checkConflict(CheckReservationConflictRequest $request)
    {
        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Store a newly created reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReservationRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Resource created",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function store(ReservationRequest $request): ReservationResource|JsonResponse
    {
        $reservation = Reservation::query()->create($request->validated());

        return ReservationResource::make($reservation->load(['patient', 'doctor', 'medicalCase']));
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/{reservation}",
     *     summary="Display the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function show(Reservation $reservation): ReservationResource
    {
        return ReservationResource::make($reservation->load(['patient', 'doctor', 'medicalCase']));
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/{reservation}",
     *     summary="Update the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReservationRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Resource updated",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function update(ReservationRequest $request, Reservation $reservation): ReservationResource|JsonResponse
    {
        $reservation->update($request->validated());

        return ReservationResource::make($reservation->load(['patient', 'doctor', 'medicalCase']));
    }

    /**
     * @OA\Delete(
     *     path="/api/reservations/{reservation}",
     *     summary="Remove the specified reservation",
     *     security={ {"bearerAuth": {} }},
     *     tags={"Reservation"},
     *
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Resource deleted successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    public function destroy(Reservation $reservation): ReservationResource
    {
        $reservation->delete();

        return ReservationResource::make($reservation);
    }
}
