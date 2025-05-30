<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="ChronicMedicationRequest",
 *     title="ChronicMedicationRequest",
 *     description="Request schema for chronic medication",
 *     required={"patient_id", "description"},
 *     @OA\Property(
 *         property="patient_id",
 *         type="string",
 *         description="The ID of the patient",
 *         example="123e4567-e89b-12d3-a456-426614174000"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the chronic medication",
 *         maxLength=255,
 *         example="Daily medication for hypertension"
 *     )
 * )
 */
class ChronicMedicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to true if authorization is not required, or add logic to check permissions.
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}