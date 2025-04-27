<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateBillingTransactionRequest",
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         description="UUID of the clinic associated with the transaction"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         enum={"recorded", "paid"},
 *         description="Type of the transaction (e.g., recorded, paid)"
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
class UpdateBillingTransactionRequest extends FormRequest
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
            'patientId' => ['nullable', 'string', Rule::exists('patients' , 'id')],
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')],
            'reservationId' => ['nullable', 'string', Rule::exists('reservations' , 'id')],
            'type' => ['sometimes', 'string', Rule::in(['recorded' , 'paid'])],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get only the validated data that was actually provided in the request
     *
     * @param string|null $key
     * @param mixed $default
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->input('clinicId') ?? Auth::user()->clinic_id,
            'user_id' => Auth::id(),
            'patient_id' => $this->safe()->patientId,
            'reservation_id' => $this->safe()->reservationId,
        ]);
    }
}
