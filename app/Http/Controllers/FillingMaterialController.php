<?php

namespace App\Http\Controllers;

use App\Models\FillingMaterial;
use Illuminate\Http\Request;
use App\Http\Resources\FillingMaterialResource;


class FillingMaterialController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/filling/materials",
     *     summary="Get all filling materials",
     *     tags={"Filling Materials"},
     *     @OA\Response(
     *         response=200,
     *         description="List of filling materials",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/FillingMaterialResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        $fillingMaterials = FillingMaterial::with('laboratory')->get();

        return FillingMaterialResource::collection($fillingMaterials);
    }

    /**
     * @OA\Post(
     *     path="/api/filling/materials",
     *     summary="Create a new filling material",
     *     tags={"Filling Materials"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Composite Resin"),
     *             @OA\Property(property="color", type="string", example="White"),
     *             @OA\Property(property="laboratoryId", type="string", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Filling material created",
     *         @OA\JsonContent(ref="#/components/schemas/FillingMaterialResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'laboratoryId' => 'required|exists:laboratories,id',
        ]);

        $fillingMaterial = FillingMaterial::query()->create([
            'name' => $validatedData['name'],
            'color' => $validatedData['color'],
            'laboratory_id' => $validatedData['laboratoryId']
        ]);

        return FillingMaterialResource::make($fillingMaterial);
    }


    /**
     * @OA\Put(
     *     path="/api/filling/materials/{fillingMaterial}",
     *     summary="Update a filling material",
     *     tags={"Filling Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the filling material",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Composite Resin"),
     *             @OA\Property(property="color", type="string", example="White"),
     *             @OA\Property(property="laboratoryId", type="string", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filling material updated",
     *         @OA\JsonContent(ref="#/components/schemas/FillingMaterialResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filling material not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(Request $request, FillingMaterial $fillingMaterial)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:255',
            'laboratoryId' => 'sometimes|exists:laboratories,id',
        ]);

        $fillingMaterial->update([
            'name' => $validatedData['name'] ?? $fillingMaterial->name,
            'color' => $validatedData['color'] ?? $fillingMaterial->color,
            'laboratory_id' => $validatedData['laboratoryId'] ?? $fillingMaterial->laboratory_id
        ]);

        return FillingMaterialResource::make($fillingMaterial);
    }

    /**
     * @OA\Delete(
     *     path="/api/filling/materials/{fillingMaterial}",
     *     summary="Delete a filling material",
     *     tags={"Filling Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the filling material",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Filling material deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filling material not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function destroy(FillingMaterial $fillingMaterial)
    {
        $fillingMaterial->delete();

        return response()->noContent();
    }
}
