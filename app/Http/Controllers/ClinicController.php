<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Models\User;
use App\DTO\ClinicDTO;
use Illuminate\Http\Response;
use App\DTO\ClinicWorkingDayDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClinicResource;
use App\Actions\CreateClinicSubscription;
use App\Http\Requests\UpdateClinicRequest;
use App\Http\Requests\ClinicSubscriptionRequest;


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
     *     path="/api/clinics",
     *     summary="Get current clinic details",
     *     tags={"Clinics"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ClinicResource")
     *     )
     * )
     */
    public function index(): ClinicResource
    {
        /** @var User $user */
        $user = Auth::user();
        return ClinicResource::make($user->clinic->load('workingDays'));
    }

    /**
     * @OA\Post(
     *     path="/api/clinics/subscription",
     *     summary="Create a new clinic subscription",
     *     tags={"Clinics"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ClinicSubscriptionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Clinic subscription created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string"),
     *             @OA\Property(property="tokenType", type="string"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(ClinicSubscriptionRequest $request, CreateClinicSubscription $action): JsonResponse
    {
        $workingDays = collect($request->validated('workingDays'))
            ->map(fn(array $day) => ClinicWorkingDayDTO::fromArray($day));

        $user = $action->handle(
            ClinicDTO::fromArray($request->clinicValidated()),
            UserDTO::fromArray($request->userValidated()),
            $workingDays
        );

        return response()->json([
            'accessToken' => $user->createToken('auth_token')->plainTextToken,
            'tokenType' => 'Bearer',
            'user' => UserResource::make($user->load('roles')),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/clinics",
     *     summary="Update clinic details",
     *     tags={"Clinics"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateClinicRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ClinicResource")
     *     )
     * )
     */
    public function update(UpdateClinicRequest $request): ClinicResource
    {
        $clinic = Auth::user()->clinic;
        $clinic->update($request->validated());

        return ClinicResource::make($clinic);
    }

    /**
     * @OA\Delete(
     *     path="/api/clinics",
     *     summary="Delete clinic and associated users",
     *     tags={"Clinics"},
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
