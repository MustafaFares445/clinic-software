<?php

namespace App\Http\Controllers;

use App\Http\Resources\LaboratoryResource;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LaboratoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/laboratories",
     *     summary="Get all laboratories",
     *     tags={"Laboratories"},
     *     @OA\Response(
     *         response=200,
     *         description="List of laboratories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LaboratoryResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $laboratories = Laboratory::all();

        return LaboratoryResource::collection($laboratories);
    }

    /**
     * @OA\Post(
     *     path="/api/laboratories",
     *     summary="Create a new laboratory",
     *     tags={"Laboratories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "address", "phone"},
     *             @OA\Property(property="name", type="string", example="Lab Name"),
     *             @OA\Property(property="address", type="string", example="123 Lab Street"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="whatsapp", type="string", example="1234567890", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Laboratory created",
     *         @OA\JsonContent(ref="#/components/schemas/LaboratoryResource")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        $laboratory = Laboratory::create(array_merge($validatedData , [
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return LaboratoryResource::make($laboratory);
    }

    /**
     * @OA\Get(
     *     path="/api/laboratories/{laboratory}",
     *     summary="Get a specific laboratory",
     *     tags={"Laboratories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Laboratory details",
     *         @OA\JsonContent(ref="#/components/schemas/LaboratoryResource")
     *     )
     * )
     */
    public function show(Laboratory $laboratory)
    {
        return LaboratoryResource::make($laboratory->load('fillingMaterials'));
    }

    /**
     * @OA\Put(
     *     path="/api/laboratories/{laboratory}",
     *     summary="Update a laboratory",
     *     tags={"Laboratories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Lab Name"),
     *             @OA\Property(property="address", type="string", example="456 Updated Lab Street"),
     *             @OA\Property(property="phone", type="string", example="0987654321"),
     *             @OA\Property(property="whatsapp", type="string", example="0987654321", nullable=true),
     *             @OA\Property(property="clinic_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Laboratory updated",
     *         @OA\JsonContent(ref="#/components/schemas/LaboratoryResource")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $laboratory = Laboratory::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'clinic_id' => 'sometimes|exists:clinics,id',
        ]);

        $laboratory->update(array_merge($validatedData , [
            'clinic_id' => Auth::user()->clinic_id
        ]));

        return LaboratoryResource::make($laboratory);
    }

    /**
     * @OA\Delete(
     *     path="/api/laboratories/{laboratory}",
     *     summary="Delete a laboratory",
     *     tags={"Laboratories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Laboratory deleted"
     *     )
     * )
     */
    public function destroy(Laboratory $laboratory)
    {
        $laboratory->delete();
        return response()->noContent();
    }
}
