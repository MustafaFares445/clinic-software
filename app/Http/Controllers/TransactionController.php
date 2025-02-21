<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return TransactionResource::collection(
            Transaction::query()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request): TransactionResource
    {
        return TransactionResource::make(
          Transaction::query()->create($request->validated())
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction): TransactionResource
    {
        return TransactionResource::make(
            $transaction->update($request->validated($request->validated()))
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): TransactionResource
    {
        $transaction->delete();
        return TransactionResource::make($transaction);
    }
}
