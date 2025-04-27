<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateBillingTransactionRequest
 *
 * @package App\Http\Requests
 *
 * @OA\Schema(
 *     schema="CreateBillingTransactionRequest",
 *     required={"type", "amount", "patientId"},
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
 *     @OA\Property(
 *         property="patientId",
 *         type="string",
 *         description="UUID of the patient associated with the transaction"
 *     ),
 *     @OA\Property(
 *         property="reservationId",
 *         type="string",
 *         description="UUID of the reservation associated with the transaction",
 *         nullable=true
 *     ),
 * )
 */
class CreateBillingTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clinicId' => ['nullable', 'string' , Rule::exists('clinics' , 'id')],
            'type' => ['required', 'string', Rule::in(['recorded' , 'paid'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'patientId' => ['required', 'string', Rule::exists('patients' , 'id')],
            'reservationId' => ['nullable', 'string', Rule::exists('reservations' , 'id')],

        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param string|null $key
     * @param mixed $default
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->input('clinicId') ?? Auth::user()->clinic_id,
            'user_id' => Auth::id(),
            'patient_id' => $this->safe()->patientId,
            'reservation_id' => $this->safe()->reservationId,
        ]);
    }
}
