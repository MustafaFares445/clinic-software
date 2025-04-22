<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="IndexBillingTransactionRequest",
 *     type="object",
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of billing transaction",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="year",
 *         type="integer",
 *         description="The year of the billing transaction",
 *         nullable=true,
 *         minimum=1900,
 *         maximum=2023
 *     )
 * )
 */
class IndexBillingTransactionRequest extends FormRequest
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
            'type' => ['nullable' , 'string' , Rule::in(['paid' , 'recorded'])],
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ];
    }
}