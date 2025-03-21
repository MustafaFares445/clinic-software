<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillingTransactionResource;
use App\Models\BillingTransaction;
use App\Http\Requests\CreateBillingTransactionRequest;
use App\Http\Requests\UpdateBillingTransactionRequest;
use Illuminate\Http\Request;

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
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by transaction type",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
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
    public function index(Request $request)
    {
        return BillingTransactionResource::collection(
            BillingTransaction::with([
                'user' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
                'model'
            ])->when(
                $request->has('type'),
                fn($query) => $query->where('type', $request->input('type'))
            )->paginate()
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateBillingTransactionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Billing transaction created",
     *         @OA\JsonContent(ref="#/components/schemas/BillingTransactionResource")
     *     )
     * )
     */
    public function store(CreateBillingTransactionRequest $request)
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
    public function show(BillingTransaction $billing)
    {
        return BillingTransactionResource::make($billing->load('user'));
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
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBillingTransactionRequest")
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
    public function update(UpdateBillingTransactionRequest $request, BillingTransaction $billing)
    {
        $billing->update($request->validated());

        return BillingTransactionResource::make($billing->refresh());
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
    public function destroy(BillingTransaction $billing)
    {
        $billing->delete();

        return response()->noContent();
    }
}