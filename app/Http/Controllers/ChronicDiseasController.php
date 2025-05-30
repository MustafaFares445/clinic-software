<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChronicDiseas;
use Illuminate\Validation\Rule;
use App\Http\Resources\ChronicDiseasResource;
use App\Http\Requests\ChronicDiseaseRequest;


class ChronicDiseasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chronics/diseases",
     *     summary="Get all chronic diseases for a patient",
     *     tags={"Chronic Diseases"},
     *     @OA\Parameter(
     *         name="patientId",
     *         in="query",
     *         required=true,
     *         description="ID of the patient",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of chronic diseases",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChronicDiseasResource"))
     *     ),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index(Request $request)
    {
        $request->validate([
            'patientId' => ['required'  , 'string' , Rule::exists('patients' , 'id')]
        ]);

        $chronicDiseases = ChronicDiseas::query()
            ->where('patient_id' , $request->get('patientId'))
            ->get();

        return ChronicDiseasResource::collection($chronicDiseases);
    }

    /**
     * @OA\Post(
     *     path="/api/chronics/diseases",
     *     summary="Create a new chronic disease",
     *     tags={"Chronic Diseases"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChronicDiseaseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chronic disease created",
     *         @OA\JsonContent(ref="#/components/schemas/ChronicDiseasResource")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(ChronicDiseaseRequest $request)
    {
        $chronicDisease = ChronicDiseas::query()->create($request->validated());

        return ChronicDiseasResource::make($chronicDisease);
    }

    /**
     * @OA\Put(
     *     path="/api/chronics/diseases/{chronicDisease}",
     *     summary="Update a chronic disease",
     *     tags={"Chronic Diseases"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chronic disease to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChronicDiseaseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chronic disease updated",
     *         @OA\JsonContent(ref="#/components/schemas/ChronicDiseasResource")
     *     ),
     *     @OA\Response(response=404, description="Chronic disease not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(ChronicDiseaseRequest $request, ChronicDiseas $chronicDisease)
    {
        $chronicDisease->update($request->validated());

        return ChronicDiseasResource::make($chronicDisease);
    }

    /**
     * @OA\Delete(
     *     path="/api/chronics/diseases/{chronicDisease}",
     *     summary="Delete a chronic disease",
     *     tags={"Chronic Diseases"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chronic disease to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No content"),
     *     @OA\Response(response=404, description="Chronic disease not found")
     * )
     */
    public function destroy(ChronicDiseas $chronicDisease)
    {
        $chronicDisease->delete();

        return response()->noContent();
    }
}
