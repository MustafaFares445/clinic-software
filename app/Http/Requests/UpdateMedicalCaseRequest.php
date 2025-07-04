<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateMedicalCaseRequest",
 *     title="Update Medical Case Request",
 *     description="Request body for updating a medical case",
 *     required={},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="The name of the medical case"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the medical case"
 *     ),
 *     @OA\Property(
 *         property="clinic_id",
 *         type="integer",
 *         description="The ID of the clinic associated with the medical case"
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="number",
 *         format="float",
 *         description="The total cost of the medical case"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="The date of the medical case"
 *     ),
 *     @OA\Property(
 *         property="patientId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the patient associated with the medical case"
 *     )
 * )
 */
class UpdateMedicalCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'clinicId' => 'nullable|exists:clinics,id',
            'total' => 'sometimes|numeric',
            'date' => 'sometimes|date',
            'patientId' => 'sometimes|string|exists:patients,id',
        ];
    }

    public function validated($key = null, $default = null)
    {
        return [
            'name' => $this->input('name'),
            'description' => $this->input('description'),
            'clinic_id' => $this->input('clinicId'),
            'total' => $this->input('total'),
            'date' => $this->input('date'),
            'patient_id' => $this->input('patientId'),
        ];
    }
}