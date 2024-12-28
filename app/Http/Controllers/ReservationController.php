<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatuses;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
           'start' => ['nullable' , 'string' , 'date'],
            'end' => ['nullable' , 'string' , 'date']
        ]);

        Reservation::query()->where('status' , ReservationStatuses::INCOME)
            ->whereDate('end' , '<' , now())
            ->update(['status' => ReservationStatuses::DISMISS]);

        $reservationQuery = Reservation::with('patient')
             ->whereDate('start' ,'>=' , $request->input('start' , now()->startOfDay()))
             ->whereDate('end' ,'<=', $request->input('end' , now()->addDays(7)->startOfDay()))
             ->when(Auth::user()->hasExactRoles('doctor') , function (Builder $query){
                 $query->where('doctor_id' , Auth::id());
             })->orderBy('start');

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
        $reservation = Reservation::query()->create($request->validated());

        return ReservationResource::make($reservation->load('patient'));
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
        return ReservationResource::collection($reservation->load('patient'));
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
        $reservation->update($request->validated());

        return ReservationResource::make($reservation->load('patient'));
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

        return ReservationResource::make($reservation->load('patient'));
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
