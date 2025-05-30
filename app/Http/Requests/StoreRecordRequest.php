<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreRecordRequest",
 *     required={"description", "type", "patientId"},
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the record"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the record"
 *     ),
 *     @OA\Property(
 *         property="treatmentId",
 *         type="string",
 *         format="uuid",
 *         description="ID of the treatment"
 *     ),
 *     @OA\Property(
 *         property="toothId",
 *         type="string",
 *         format="uuid",
 *         description="ID of the tooth"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         format="uuid",
 *         description="ID of the clinic"
 *     ),
 *     @OA\Property(
 *         property="fillingMaterialId",
 *         type="string",
 *         format="uuid",
 *         description="ID of the filling material"
 *     ),
 *     @OA\Property(
 *         property="medicalSessionId",
 *         type="string",
 *         format="uuid",
 *         description="ID of the medical session"
 *     ),
 *     @OA\Property(
 *         property="patientId",
 *         type="string",
 *         format="uuid",
 *         description="ID of the patient"
 *     )
 * )
 */
class StoreRecordRequest extends FormRequest
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
            'description' => 'required|string',
            'type' => 'required|string',
            'treatmentId' => 'nullable|uuid|exists:treatments,id',
            'toothId' => 'nullable|uuid|exists:teeth,id',
            'clinicId' => 'nullable|uuid|exists:clinics,id',
            'fillingMaterialId' => 'nullable|uuid|exists:filling_materials,id',
            'medicalSessionId' => 'nullable|uuid|exists:medical_sessions,id',
            'patientId' => 'required|uuid|exists:patients,id',
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