<?php

namespace App\Http\Requests;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BillingTransactionRequest",
 *     required={"type", "amount"},
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         description="UUID of the clinic associated with the transaction"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the transaction (e.g., in, out)"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         description="Transaction amount"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Optional description of the transaction",
 *         nullable=true
 *     ),
 * )
 */
class BillingTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clinicId' => 'nullable|exists:clinics,id',
            'type' => 'required|string|in:out,in',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->input('clinicId') ?? Auth::user()->clinic_id,
        ]);
    }
}