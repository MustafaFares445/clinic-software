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


/**
 * @OA\Tag(
 *     name="Clinics",
 *     description="Operations related to Clinics"
 * )
 */
final class ClinicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clinic",
     *     summary="Get current clinic details",
     *     tags={"Clinic"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ClinicResource")
     *     )
     * )
     */
    public function show(): ClinicResource
    {
        return ClinicResource::make(Auth::user()->clinic);
    }

    /**
     * @OA\Post(
     *     path="/api/clinic",
     *     summary="Create a new clinic",
     *     tags={"Clinic"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ClinicSubscriptionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string"),
     *             @OA\Property(property="tokenType", type="string"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/clinic",
     *     summary="Update clinic details",
     *     tags={"Clinic"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ClinicRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ClinicResource")
     *     )
     * )
     */
    public function update(ClinicRequest $request): ClinicResource
    {
        $clinic = Auth::user()->clinic;
        $clinic->update($request->validated());

        return ClinicResource::make($clinic);
    }

    /**
     * @OA\Delete(
     *     path="/api/clinic",
     *     summary="Delete clinic and associated users",
     *     tags={"Clinic"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     )
     * )
     */
    public function destroy(): Response
    {
        $clinic = Auth::user()->clinic;
        User::query()->where('clinic_id', $clinic->id)->delete();
        $clinic->delete();

        return response()->noContent();
    }
}
