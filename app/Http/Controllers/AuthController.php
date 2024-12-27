<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="register a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userName", "password"},
     *             @OA\Property(property="userName", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="secret")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="authorisation", type="object",
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="type", type="string", example="bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
            'userName' => ['required' , 'string' , Rule::unique('users' , 'userName')]
        ]);

        $user = User::query()->create([
            'userName' => $request->input('userName'),
            'password' => $request->input('password'),
            'is_verified' => true,
            'admin' => false,
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
        ]);

        return response()->json([
            'status' => 'success',
            'user' => UserResource::make($user),
            'authorisation' => [
                'token' => JWTAuth::fromUser($user),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userName" , "password"},
     *             @OA\Property(property="userName", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="secret")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="authorisation", type="object",
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="type", type="string", example="bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'nullable|string',
            'userName' => ['required' , 'string']
        ]);

        $user = User::query()->where('userName' , $request->input('userName'))->first();

        if (!$user || !Hash::check($request->input('password') , $user->password) ){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user' => UserResource::make($user),
            'authorisation' => [
                'token' => auth()->login($user),
                'type' => 'bearer',
            ]
        ]);

    }
}
