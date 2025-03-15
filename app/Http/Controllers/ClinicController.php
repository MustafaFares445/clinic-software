<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicRequest;
use App\Http\Requests\ClinicSubscriptionRequest;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\UserResource;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class ClinicController extends Controller
{
    public function show(): ClinicResource
    {
        return ClinicResource::make(Auth::user()->clinic);
    }

    public function store(ClinicSubscriptionRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $clinic = Clinic::query()->create($request->clinicValidated());
            $user = User::query()->create(array_merge($request->userValidated(), [
                'clinic_id' => $clinic->id,
            ]));

            $user->assignRole('admin');

            return response()->json([
                'accessToken' => $user->createToken('auth_token')->plainTextToken,
                'tokenType' => 'Bearer',
                'user' => UserResource::make($user->load('roles')),
            ]);
        });
    }

    public function update(ClinicRequest $request): ClinicResource
    {
        $clinic = Auth::user()->clinic;
        $clinic->update($request->validated());

        return ClinicResource::make($clinic);
    }

    public function destroy(): Response
    {
        $clinic = Auth::user()->clinic;
        User::query()->where('clinic_id', $clinic->id)->delete();
        $clinic->delete();

        return response()->noContent();
    }
}
