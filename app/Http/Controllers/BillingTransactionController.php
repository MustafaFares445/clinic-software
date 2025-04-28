<?php

namespace App\Http\Controllers;

use App\Models\BillingTransaction;
use App\Services\BillingTransactionQueryService;
use App\Services\BillingTransactionSearchService;
use App\Http\Resources\BillingTransactionResource;
use App\Http\Requests\IndexBillingTransactionRequest;
use App\Http\Requests\CreateBillingTransactionRequest;
use App\Http\Requests\UpdateBillingTransactionRequest;

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
     *     summary="List all billing transactions with filters",
     *     tags={"Billing Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by transaction type (paid or recorded)",
     *         required=false,
     *         @OA\Schema(type="string", example="paid")
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Filter by transaction year",
     *         required=false,
     *         @OA\Schema(type="integer", example=2023)
     *     ),
     *     @OA\Parameter(
     *         name="patientName",
     *         in="query",
     *         description="Filter by patient ID",
     *         required=false,
     *         @OA\Schema(type="string", example="John Doe")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date for date range filter (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date for date range filter (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Items per page (default: 15)",
     *         required=false,
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BillingTransactionResource")
     *             ),
     *             @OA\Property(property="links"),
     *             @OA\Property(property="meta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function index(IndexBillingTransactionRequest $request)
    {
        return BillingTransactionResource::collection(
            BillingTransactionSearchService::make()
                ->filterByType($request->string('type'))
                ->filterByYear($request->integer('year'))
                ->filterByPatient($request->string('patientName'))
                ->filterByDuration($request->string('startDate') , $request->string('endDate'))
                ->getQuery()
                ->with([
                    'user' => fn($query) => $query->select(['id', 'firstName', 'lastName']),
                    'patient' => fn($query) => $query->select(['id', 'firstName', 'lastName']),
                    'reservation' => fn($query) => $query->select(['id', 'start', 'end'])
                ])
                ->paginate(request()->integer('perPage' , 15))
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
