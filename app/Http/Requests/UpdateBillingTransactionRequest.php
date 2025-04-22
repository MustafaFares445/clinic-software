<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')],
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
        $validated = parent::validated($key, $default);

        // Filter out values that weren't actually in the request
        return array_filter($validated, fn($value, $key) => $this->has($key), ARRAY_FILTER_USE_BOTH);
    }
}
