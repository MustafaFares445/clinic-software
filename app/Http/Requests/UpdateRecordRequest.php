<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateRecordRequest",
 *     type="object",
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the record"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of the record"
 *     ),
 *     @OA\Property(
 *         property="treatmentId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the treatment associated with the record"
 *     ),
 *     @OA\Property(
 *         property="toothId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the tooth associated with the record"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the clinic associated with the record"
 *     ),
 *     @OA\Property(
 *         property="fillingMaterialId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the filling material associated with the record"
 *     ),
 *     @OA\Property(
 *         property="medicalSessionId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the medical session associated with the record"
 *     ),
 *     @OA\Property(
 *         property="patientId",
 *         type="string",
 *         format="uuid",
 *         description="The ID of the patient associated with the record"
 *     )
 * )
 */
final class UpdateRecordRequest extends FormRequest
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
            'description' => 'sometimes|string',
            'type' => 'sometimes|string',
            'treatmentId' => 'sometimes|uuid|exists:treatments,id',
            'toothId' => 'sometimes|uuid|exists:teeth,id',
            'clinicId' => 'sometimes|uuid|exists:clinics,id',
            'fillingMaterialId' => 'sometimes|uuid|exists:filling_materials,id',
            'medicalSessionId' => 'sometimes|uuid|exists:medical_sessions,id',
            'patientId' => 'sometimes|uuid|exists:patients,id',
        ];
    }

    public function validated($key = null, $default = null)
    {
        return [
            'description' => $this->input('description'),
            'type' => $this->input('type'),
            'treatment_id' => $this->input('treatmentId'),
            'tooth_id' => $this->input('toothId'),
            'clinic_id' => $this->input('clinicId'),
            'filling_material_id' => $this->input('fillingMaterialId'),
            'medical_session_id' => $this->input('medicalSessionId'),
            'patient_id' => $this->input('patientId'),
        ];
    }
}