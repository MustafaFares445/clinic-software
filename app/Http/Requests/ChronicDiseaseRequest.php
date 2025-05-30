<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ChronicDiseaseRequest",
 *     type="object",
 *     required={"patient_id", "description"},
 *     @OA\Property(
 *         property="patient_id",
 *         type="integer",
 *         description="The ID of the patient",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the chronic disease",
 *         example="Diabetes"
 *     )
 * )
 */
class ChronicDiseaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'description' => 'required|string|min:1|max:1000',
        ];
    }

    public function validated($key = null, $default = null)
    {
        return [
            'patient_id' => $this->input('patientId'),
            'description' => $this->input('description')
        ];
    }
}