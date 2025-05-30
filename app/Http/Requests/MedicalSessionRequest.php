<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="MedicalSessionRequest",
 *     title="MedicalSessionRequest",
 *     description="Request schema for creating or updating a medical session",
 *     required={"medicalCaseId", "date"},
 *     @OA\Property(
 *         property="medicalCaseId",
 *         type="string",
 *         description="The ID of the medical case",
 *         example="123e4567-e89b-12d3-a456-426614174000"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="The date of the medical session",
 *         example="2023-10-01"
 *     )
 * )
 */
class MedicalSessionRequest extends FormRequest
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
            'medicalCaseId' => ['required' , 'string' , Rule::exists('medical_cases' , 'id')],
            'date' => ['required' , 'date' , 'date_format:Y-m-d',]
        ];
    }
}