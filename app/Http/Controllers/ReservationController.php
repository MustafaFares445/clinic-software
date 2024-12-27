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
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $start = now()->startOfDay();
        if (now()->isLastWeek())
           $start = now()->startOfWeek();

        $reservationQuery = Reservation::with('patient')
             ->whereDate('start' ,'>=' , $request->input('start' , $start))
             ->whereDate('end' ,'<=', $request->input('end' , now()->lastOfMonth()))
             ->when(Auth::user()->hasExactRoles('doctor') , function (Builder $query){
                 $query->where('doctor_id' , Auth::id());
             });

        return ReservationResource::collection($reservationQuery->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request): ReservationResource
    {
        $reservation = Reservation::query()->create($request->validated());

        return ReservationResource::make($reservation->load('patient'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): AnonymousResourceCollection
    {
        return ReservationResource::collection($reservation->load('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationRequest $request, Reservation $reservation): ReservationResource
    {
        $reservation->update($request->validated());

        return ReservationResource::make($reservation->load('patient'));
    }

    public function changeStatus(Request $request, Reservation $reservation): ReservationResource
    {
        $request->validate([
            'status' => ['required' , 'string' , Rule::in(ReservationStatuses::values())]
        ]);

        $reservation->update(['status' => $request->input('status')]);

        return ReservationResource::make($reservation->load('patient'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): Response
    {
        $reservation->delete();

        return response()->noContent();
    }
}
