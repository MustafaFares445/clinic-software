<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChronicMedication;
use Illuminate\Validation\Rule;
use App\Http\Resources\ChronicMedicationResource;
use App\Http\Requests\ChronicMedicationRequest;

class ChronicMedicationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chronics/medications",
     *     summary="Get all chronic medications for a patient",
     *     tags={"Chronic Medications"},
     *     @OA\Parameter(
     *         name="patientId",
     *         in="query",
     *         required=true,
     *         description="ID of the patient",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of chronic medications",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChronicMedicationResource"))
     *     ),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index(Request $request)
    {
        $request->validate([
            'patientId' => ['required', 'string', Rule::exists('patients', 'id')]
        ]);

        $chronicMedications = ChronicMedication::query()
            ->where('patient_id', $request->get('patientId'))
            ->get();

        return ChronicMedicationResource::collection($chronicMedications);
    }

    /**
     * @OA\Post(
     *     path="/api/chronics/medications",
     *     summary="Create a new chronic medication",
     *     tags={"Chronic Medications"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChronicMedicationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chronic medication created",
     *         @OA\JsonContent(ref="#/components/schemas/ChronicMedicationResource")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(ChronicMedicationRequest $request)
    {
        $chronicMedication = ChronicMedication::query()->create($request->validated());

        return ChronicMedicationResource::make($chronicMedication);
    }

    /**
     * @OA\Put(
     *     path="/api/chronics/medications/{chronicMedication}",
     *     summary="Update a chronic medication",
     *     tags={"Chronic Medications"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chronic medication to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChronicMedicationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chronic medication updated",
     *         @OA\JsonContent(ref="#/components/schemas/ChronicMedicationResource")
     *     ),
     *     @OA\Response(response=404, description="Chronic medication not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(ChronicMedicationRequest $request, ChronicMedication $chronicMedication)
    {
        $chronicMedication->update($request->validated());

        return ChronicMedicationResource::make($chronicMedication);
    }

    /**
     * @OA\Delete(
     *     path="/api/chronics/medications/{chronicMedication}",
     *     summary="Delete a chronic medication",
     *     tags={"Chronic Medications"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chronic medication to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No content"),
     *     @OA\Response(response=404, description="Chronic medication not found")
     * )
     */
    public function destroy(ChronicMedication $chronicMedication)
    {
        $chronicMedication->delete();

        return response()->noContent();
    }
}