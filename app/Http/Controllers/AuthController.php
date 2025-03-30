<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints"
 * )
 */
final class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/AuthRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="accessToken", type="string"),
     *             @OA\Property(property="tokenType", type="string"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function register(AuthRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        $token = $user->createToken('auth_token');
        return response()->json([
            'accessToken' => $token->plainTextToken,
            'tokenType' => 'Bearer',
            'expiresAt' => $token->accessToken->expires_at,
            'user' => UserResource::make($user),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="username", type="string" , default="doctor-admin"),
     *             @OA\Property(property="password", type="string" , default="secret")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="accessToken", type="string"),
     *             @OA\Property(property="tokenType", type="string"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::query()->where('username', $request->input('username'))->first();

        // Check if the user exists and the password is correct
        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Check if the user is banned
            if ($user->is_banned) {
                return response()->json(
                    ['error' => 'Your account is banned. Please contact your administrator.'],
                    ResponseAlias::HTTP_FORBIDDEN
                );
            }

            return response()->json([
                'accessToken' => $user->createToken('auth_token')->plainTextToken,
                'tokenType' => 'Bearer',
                'user' => UserResource::make($user->load('clinic.workingDays')),
            ]);
        }

        return response()->json(
            ['message' => 'Unauthorized'],
            ResponseAlias::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout a user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
