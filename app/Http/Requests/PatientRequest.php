<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="PatientRequest",
 *     type="object",
 *     title="Patient Request",
 *     description="Request body data for creating or updating a patient",
 *     required={"firstName", "lastName"},
 *
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         description="The first name of the patient"
 *     ),
 *     @OA\Property(
 *         property="lastName",
 *         type="string",
 *         description="The last name of the patient"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         nullable=true,
 *         description="The phone number of the patient"
 *     ),
 *     @OA\Property(
 *         property="age",
 *         type="number",
 *         format="float",
 *         nullable=true,
 *         description="The age of the patient"
 *     ),
 *     @OA\Property(
 *         property="fatherName",
 *         type="string",
 *         nullable=true,
 *         description="The father's name of the patient"
 *     ),
 *     @OA\Property(
 *         property="motherName",
 *         type="string",
 *         nullable=true,
 *         description="The mother's name of the patient"
 *     ),
 *     @OA\Property(
 *         property="nationalNumber",
 *         type="string",
 *         nullable=true,
 *         description="The national number of the patient"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         nullable=true,
 *         description="The address of the patient"
 *     ),
 *     @OA\Property(
 *         property="notes",
 *         type="string",
 *         nullable=true,
 *         description="Additional notes about the patient"
 *     ),
 *     @OA\Property(
 *         property="birth",
 *         type="string",
 *         nullable=true,
 *         description="The birth date of the patient"
 *     ),
 *     @OA\Property(
 *         property="gender",
 *         type="string",
 *         enum={"male", "female"},
 *         nullable=true,
 *         description="The gender of the patient"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         nullable=true,
 *         description="The ID of the clinic the patient belongs to"
 *     )
 * )
 */
final class PatientRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'firstName' => ['sometimes', 'string'],
            'lastName' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'nullable', 'string'],
            'age' => ['sometimes', 'nullable', 'numeric'],
            'fatherName' => ['sometimes', 'nullable', 'string'],
            'motherName' => ['sometimes', 'nullable', 'string'],
            'nationalNumber' => ['sometimes', 'nullable', 'string'],
            'address' => ['sometimes', 'nullable', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'birth' => ['sometimes', 'nullable', 'string'],
            'gender' => ['sometimes', 'nullable', 'string', Rule::in(['female', 'male'])],
            'clinicId' => ['sometimes', 'nullable', 'string', Rule::exists('clinics', 'id')],
        ];

        if ($this->isMethod('POST')) {
            $rules['firstName'] = ['required', 'string'];
            $rules['lastName'] = ['required', 'string'];
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->input('clinicId') ?? Auth::user()->clinic_id,
        ]);
    }
}
