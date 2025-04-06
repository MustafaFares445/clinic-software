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
 *     ),
 *     @OA\Property(
 *         property="permanentMedicines",
 *         type="array",
 *         nullable=true,
 *         description="Array of permanent medicines for the patient",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="string", description="Medicine ID"),
 *             @OA\Property(property="notes", type="string", nullable=true, description="Notes about the medicine")
 *         )
 *     ),
 *     @OA\Property(
 *         property="permanentIlls",
 *         type="array",
 *         nullable=true,
 *         description="Array of permanent illnesses for the patient",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="string", description="Illness ID"),
 *             @OA\Property(property="notes", type="string", nullable=true, description="Notes about the illness")
 *         )
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
            'fatherName' => ['sometimes', 'nullable', 'string'],
            'motherName' => ['sometimes', 'nullable', 'string'],
            'nationalNumber' => ['sometimes', 'nullable', 'string' , Rule::unique('patients' , 'nationalNumber')],
            'address' => ['sometimes', 'nullable', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'birth' => ['sometimes', 'nullable', 'string'],
            'gender' => ['sometimes', 'nullable', 'string', Rule::in(['female', 'male'])],
            'clinicId' => ['sometimes', 'nullable', 'string', Rule::exists('clinics', 'id')],
            'permanentMedicines' => ['sometimes', 'nullable', 'array'],
            'permanentMedicines.*.id' => ['exists:medicines,id'],
            'permanentMedicines.*.notes' => ['nullable', 'string'],
            'permanentIlls' => ['sometimes', 'nullable', 'array'],
            'permanentIlls.*.id' => ['exists:ills,id'],
            'permanentIlls.*.notes' => ['nullable', 'string'],
            'profileImage' => ['sometimes' , 'image' , 'mimes:png,jpg,webp']
        ];

        if ($this->isMethod('POST')) {
            $rules['firstName'] = ['required', 'string'];
            $rules['lastName'] = ['required', 'string'];
            $rules['nationalNumber'] = ['required', 'string' , Rule::unique('patients' , 'nationalNumber')];
        }

        return $rules;
    }
}
