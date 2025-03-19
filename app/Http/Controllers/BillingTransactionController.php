<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillingTransactionResource;
use App\Models\BillingTransaction;
use App\Http\Requests\BillingTransactionRequest;

/**
 * @OA\Tag(
 *     name="Billing Transactions",
 *     description="Operations related to billing transactions"
 * )
 */
class BillingTransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transactions/billing",
     *     summary="List all billing transactions",
     *     tags={"Billing Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BillingTransactionResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return BillingTransactionResource::collection(
            BillingTransaction::with([
                'user' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
                'model'
            ])->paginate()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/transactions/billing",
     *     summary="Create a new billing transaction",
     *     tags={"Billing Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BillingTransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Billing transaction created",
     *         @OA\JsonContent(ref="#/components/schemas/BillingTransactionResource")
     *     )
     * )
     */
    public function store(BillingTransactionRequest $request)
    {
        return BillingTransactionResource::make(
            BillingTransaction::create($request->validated())
        );
    }

    /**
     * @OA\Get(
     *     path="/api/transactions/billing/{billingTransaction}",
     *     summary="Get a specific billing transaction",
     *     tags={"Billing Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="UUID of the billing transaction",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BillingTransactionResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Billing transaction not found"
     *     )
     * )
     */
    public function show(BillingTransaction $billingTransaction)
    {
        return BillingTransactionResource::make($billingTransaction);
    }

    /**
     * @OA\Put(
     *     path="/api/transactions/billing/{billingTransaction}",
     *     summary="Update a billing transaction",
     *     tags={"Billing Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="UUID of the billing transaction",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BillingTransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Billing transaction updated",
     *         @OA\JsonContent(ref="#/components/schemas/BillingTransactionResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Billing transaction not found"
     *     )
     * )
     */
    public function update(BillingTransactionRequest $request, BillingTransaction $billingTransaction)
    {
        return BillingTransactionResource::make(
            $billingTransaction->update($request->validated())
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/transactions/billing/{billingTransaction}",
     *     summary="Delete a billing transaction",
     *     tags={"Billing Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="UUID of the billing transaction",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Billing transaction not found"
     *     )
     * )
     */
    public function destroy(BillingTransaction $billingTransaction)
    {
        $billingTransaction->delete();

        return response()->noContent();
    }
}