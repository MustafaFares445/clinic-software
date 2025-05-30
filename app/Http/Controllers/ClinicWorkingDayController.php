<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClinicWorkingDay;
use App\DTOs\ClinicWorkingDayDTO;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ClinicWorkingDayRequest;
use App\Http\Resources\ClinicWorkingDayResource;

class ClinicWorkingDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return ClinicWorkingDayResource::collection(
          $user->clinic->workingDays
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClinicWorkingDayRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $workingDay = $user->clinic->workingDays()
            ->create(ClinicWorkingDayDTO::fromArray($request->validated())->toArray());

        return ClinicWorkingDayResource::make($workingDay);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClinicWorkingDayRequest $request, ClinicWorkingDay $clinicWorkingDay)
    {
        $clinicWorkingDay->update(
    ClinicWorkingDayDTO::fromArray($request->validated())->toArray()
        );

        $clinicWorkingDay->refresh();

        return ClinicWorkingDayResource::make($clinicWorkingDay);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicWorkingDay $clinicWorkingDay)
    {
        $clinicWorkingDay->delete();

        return ClinicWorkingDayResource::make($clinicWorkingDay);
    }
}
