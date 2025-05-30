<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use App\Http\Requests\StoreMedicalCaseRequest;
use App\Http\Requests\UpdateMedicalCaseRequest;
use App\Http\Resources\MedicalCaseResource;
use App\Http\Resources\MedicalSessionResource;

class MedicalCaseController extends Controller
{
    /**
     * Store a newly created medical case in storage.
     *
     * @OA\Post(
     *     path="/api/medical/cases",
     *     summary="Create a new medical case",
     *     tags={"Medical Cases"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreMedicalCaseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medical case created",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalCaseResource")
     *     )
     * )
     */
    public function store(StoreMedicalCaseRequest $request)
    {
        $medicalCase = MedicalCase::create($request->validated());

        return MedicalCaseResource::make($medicalCase);
    }

    /**
     * Update the specified medical case in storage.
     *
     * @OA\Put(
     *     path="/medical-cases/{id}",
     *     summary="Update a specific medical case",
     *     tags={"Medical Cases"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the medical case to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateMedicalCaseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medical case updated",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalCaseResource")
     *     )
     * )
     */
    public function update(UpdateMedicalCaseRequest $request, MedicalCase $medicalCase)
    {
        $medicalCase->update($request->validated());

        return MedicalCaseResource::make($medicalCase);
    }

    /**
     * Remove the specified medical case from storage.
     *
     * @OA\Delete(
     *     path="/medical-cases/{id}",
     *     summary="Delete a specific medical case",
     *     tags={"Medical Cases"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the medical case to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Medical case deleted"
     *     )
     * )
     */
    public function destroy(MedicalCase $medicalCase)
    {
        $medicalCase->delete();

        return response()->noContent(null, 204);
    }
}