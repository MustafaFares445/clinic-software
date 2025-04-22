<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillingTransactionResource;
use App\Models\BillingTransaction;
use App\Http\Requests\CreateBillingTransactionRequest;
use App\Http\Requests\UpdateBillingTransactionRequest;
use App\Http\Requests\IndexBillingTransactionRequest;

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
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Filter by year of transaction",
     *         required=false,
     *         @OA\Schema(type="integer")
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
    public function index(IndexBillingTransactionRequest $request)
    {
        return BillingTransactionResource::collection(
            BillingTransaction::with([
                'user' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
                'patient' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
                'reservation' => fn($query) => $query->select(['id' , 'start' , 'end'])
            ])->when(
                $request->has('type'),
                fn($query) => $query->where('type', $request->input('type'))
            )->when(
                $request->has('year'),
                fn($query) => $query->whereYear('created_at', $request->input('year'))
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
        $billing = BillingTransaction::query()->create($request->validated());

        return BillingTransactionResource::make($billing->load([
            'user' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
            'patient' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
            'reservation' => fn($query) => $query->select(['id' , 'start' , 'end'])
        ]));
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
        return BillingTransactionResource::make($billing->load([
            'user' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
            'patient' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
            'reservation' => fn($query) => $query->select(['id' , 'start' , 'end'])
        ]));
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

        return BillingTransactionResource::make($billing->refresh()->load([
            'user' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
            'patient' => fn($query) => $query->select(['id' , 'firstName' , 'lastName']),
            'reservation' => fn($query) => $query->select(['id' , 'start' , 'end'])
        ]));
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
