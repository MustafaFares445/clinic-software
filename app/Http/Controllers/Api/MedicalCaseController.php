<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalCaseRequest;
use App\Http\Resources\MedicalCaseResource;
use App\Models\MedicalCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MedicalCaseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $medicalCases = MedicalCase::with(['clinic', 'createdBy', 'patient', 'medicalSessions'])->get();
        return MedicalCaseResource::collection($medicalCases);
    }

    public function store(MedicalCaseRequest $request): MedicalCaseResource
    {
        $medicalCase = MedicalCase::create($request->validated());
        return new MedicalCaseResource($medicalCase);
    }

    public function show(MedicalCase $medicalCase): MedicalCaseResource
    {
        return new MedicalCaseResource($medicalCase->load(['clinic', 'createdBy', 'patient', 'medicalSessions']));
    }

    public function update(MedicalCaseRequest $request, MedicalCase $medicalCase): MedicalCaseResource
    {
        $medicalCase->update($request->validated());
        return new MedicalCaseResource($medicalCase);
    }

    public function destroy(MedicalCase $medicalCase): JsonResponse
    {
        $medicalCase->delete();
        return response()->json(null, 204);
    }
}