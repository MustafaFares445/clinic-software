<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="PatientRecordsRequest",
 *     title="Patient Records Request",
 *     description="Request parameters for filtering patient records",
 *     type="object",
 *     @OA\Property(
 *         property="startDate",
 *         type="string",
 *         format="date",
 *         description="Start date for filtering records",
 *         example="2023-01-01"
 *     ),
 *     @OA\Property(
 *         property="endDate",
 *         type="string",
 *         format="date",
 *         description="End date for filtering records",
 *         example="2023-12-31"
 *     ),
 *     @OA\Property(
 *         property="search",
 *         type="string",
 *         description="Search term for filtering records",
 *         example="John Doe"
 *     )
 * )
 */
class PatientRecordsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'startDate' => ['nullable', 'date', 'before_or_equal:endDate'],
            'endDate' => ['nullable', 'date', 'after_or_equal:startDate'],
            'search' => ['nullable' , 'string' , 'max:255']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'startDate.date' => 'The start date must be a valid date.',
            'startDate.before_or_equal' => 'The start date must be before or equal to the end date.',
            'endDate.date' => 'The end date must be a valid date.',
            'endDate.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}