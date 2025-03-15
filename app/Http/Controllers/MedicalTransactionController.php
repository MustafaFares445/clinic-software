<?php

namespace App\Http\Controllers;

use App\Models\MedicalTransactions;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalTransactionRequest;
use App\Http\Resources\MedicalResource;
use App\Http\Resources\MedicalTransactionResource;

/**
 * @OA\Tag(
 *     name="Medical Transactions",
 *     description="Operations related to medical transactions"
 * )
 */
class MedicalTransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transactions/medical",
     *     summary="List all medical transactions",
     *     tags={"Medical Transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MedicalTransactionResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return MedicalTransactionResource::collection(
             MedicalTransactions::with([
                'doctor' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
                'record' => fn($query) => $query->select(['id' ,'patient_id']),
                'record.patient' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
                'record.patient.media',
                'doctor.media'
            ])->paginate()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/transactions/medical",
     *     summary="Create a new medical transaction",
     *     tags={"Medical Transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicalTransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medical transaction created",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalTransactionResource")
     *     )
     * )
     */
    public function store(MedicalTransactionRequest $request)
    {
        return MedicalTransactionResource::make(
            MedicalTransactions::create($request->validated())
        );
    }

    /**
     * @OA\Get(
     *     path="/api/transactions/medical/{medicalTransaction}",
     *     summary="Get a specific medical transaction",
     *     tags={"Medical Transactions"},
     *     @OA\Parameter(
     *         name="medicalTransaction",
     *         in="path",
     *         required=true,
     *         description="ID of the medical transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalTransactionResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medical transaction not found"
     *     )
     * )
     */
    public function show(MedicalTransactions $medicalTransaction)
    {
        return MedicalTransactionResource::make($medicalTransaction->load('doctor'));
    }

    /**
     * @OA\Put(
     *     path="/api/transactions/medical/{medicalTransaction}",
     *     summary="Update a medical transaction",
     *     tags={"Medical Transactions"},
     *     @OA\Parameter(
     *         name="medicalTransaction",
     *         in="path",
     *         required=true,
     *         description="ID of the medical transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicalTransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medical transaction updated",
     *         @OA\JsonContent(ref="#/components/schemas/MedicalTransactionResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medical transaction not found"
     *     )
     * )
     */
    public function update(MedicalTransactionRequest $request, MedicalTransactions $medicalTransaction)
    {
        return MedicalTransactionResource::make(
            $medicalTransaction->update($request->validated())
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/transactions/medical/{medicalTransaction}",
     *     summary="Delete a medical transaction",
     *     tags={"Medical Transactions"},
     *     @OA\Parameter(
     *         name="medicalTransaction",
     *         in="path",
     *         required=true,
     *         description="ID of the medical transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medical transaction not found"
     *     )
     * )
     */
    public function destroy(MedicalTransactions $medicalTransaction)
    {
        $medicalTransaction->delete();

        return response()->noContent();
    }
}