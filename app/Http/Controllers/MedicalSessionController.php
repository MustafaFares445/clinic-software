<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalSessionRequest;
use App\Models\MedicalSession;
use App\Http\Resources\MedicalSessionResource;

class MedicalSessionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/medical/sessions",
     *     summary="Create a new medical session",
     *     tags={"Medical Sessions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicalSessionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medical session created",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalSessionResource")
     *     )
     * )
     */
    public function store(MedicalSessionRequest $request)
    {
        $medicalSession = MedicalSession::create($request->validated());

        return MedicalSessionResource::make($medicalSession);
    }

    /**
     * @OA\Put(
     *     path="/api/medical/sessions/{medicalSession}",
     *     summary="Update a specific medical session",
     *     tags={"Medical Sessions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the medical session to update",
     *         @OA\Schema(type="string" , format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicalSessionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medical session updated",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalSessionResource")
     *     )
     * )
     */
    public function update(MedicalSessionRequest $request, MedicalSession $medicalSession)
    {
        $medicalSession->update($request->validated());

        return MedicalSessionResource::make($medicalSession);
    }

    /**
     * @OA\Delete(
     *     path="/api/medical/sessions/{medicalSession}",
     *     summary="Delete a specific medical session",
     *     tags={"Medical Sessions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the medical session to delete",
     *         @OA\Schema(type="string" , format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Medical session deleted"
     *     )
     * )
     */
    public function destroy(MedicalSession $medicalSession)
    {
        $medicalSession->delete();

        return response()->noContent();
    }
}