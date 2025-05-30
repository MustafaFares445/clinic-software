<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


final class FeedbackController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/feedback",
     *     summary="Create a new feedback",
     *     tags={"Feedback"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"description"},
     *             @OA\Property(property="description", type="string", example="This is a sample feedback description"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Feedback created successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|min:1|max:1000',
        ]);

        $feedback = Feedback::create(array_merge($validatedData , [
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return response()->noContent();
    }
}
