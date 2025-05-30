<?php

namespace App\Http\Controllers;

use App\Http\Resources\TreatmentResource;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TreatmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/treatments",
     *     summary="Get all treatments",
     *     tags={"Treatments"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TreatmentResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $treatments = Treatment::all();

        return TreatmentResource::collection($treatments);
    }

    /**
     * @OA\Post(
     *     path="/api/treatments",
     *     summary="Create a new treatment",
     *     tags={"Treatments"},
     *     @OA\Response(
     *         response=201,
     *         description="Treatment created",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        $treatment = Treatment::create(array_merge($validatedData , [
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return TreatmentResource::make($treatment);
    }

    /**
     * @OA\Put(
     *     path="/api/treatments/{treatment}",
     *     summary="Update a treatment",
     *     tags={"Treatments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string" , format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Treatment Name"),
     *             @OA\Property(property="color", type="string", example="#000000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Treatment updated",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:255',
        ]);

        $treatment->update(array_merge($validatedData , [
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return TreatmentResource::make($treatment);
    }

    /**
     * @OA\Delete(
     *     path="/api/treatments/{treatment}",
     *     summary="Delete a treatment",
     *     tags={"Treatments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string" , format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Treatment deleted"
     *     )
     * )
     */
    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

        return response()->noContent();
    }
}
