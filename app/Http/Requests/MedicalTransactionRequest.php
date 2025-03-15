<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="MedicalTransactionRequest",
 *     required={"transaction_date", "amount", "status"},
 *     @OA\Property(property="clinicId", type="integer", example=1, description="ID of the clinic"),
 *     @OA\Property(property="transaction_date", type="string", format="date", example="2023-01-01", description="Date of the transaction"),
 *     @OA\Property(property="amount", type="number", format="float", example=100.50, description="Transaction amount"),
 *     @OA\Property(property="notes", type="string", maxLength=500, example="Sample notes", description="Additional notes"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "cancelled"}, example="pending", description="Transaction status")
 * )
 */
class MedicalTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'clinicId' => 'nullable|exists:clinics,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,completed,cancelled'
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->safe()->clinicId ?? Auth::user()->clinic_id,
        ]);
    }
}
