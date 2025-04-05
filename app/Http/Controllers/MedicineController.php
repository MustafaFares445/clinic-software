<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicineRequest;
use App\Http\Resources\MedicineResource;
use App\Models\Medicine;

/**
 * @OA\Tag(
 *     name="Medicines",
 *     description="Operations related to Medicines"
 * )
 */
class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/medicines",
     *     summary="Get list of medicines",
     *     description="Returns list of all medicines",
     *     operationId="getMedicinesList",
     *     tags={"Medicines"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MedicineResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
       return MedicineResource::collection(Medicine::query()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/medicines",
     *     summary="Create a new medicine",
     *     description="Creates a new medicine with the provided data",
     *     operationId="storeMedicine",
     *     tags={"Medicines"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicineRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medicine created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/MedicineResource")
     *     )
     * )
     */
    public function store(MedicineRequest $request)
    {
        $medicine = Medicine::create($request->validated());

        if ($request->has('specifications')) {
            $medicine->specifications()->sync($request->input('specifications'));
        }

        return MedicineResource::make($medicine->load('specifications'));
    }
}
