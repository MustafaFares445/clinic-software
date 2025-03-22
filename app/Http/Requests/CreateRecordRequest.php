<?php

namespace App\Http\Requests;

use App\Enums\RecordTypes;
use App\Enums\RecordIllsTypes;
use Illuminate\Validation\Rule;
use App\Enums\RecordMedicinesTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @OA\Schema(
 *     schema="CreateRecordRequest",
 *     title="Record Request",
 *     description="Record request body for creating or updating records",
 *     required={"patientId", "type"},
 *     @OA\Property(
 *         property="patientId",
 *         type="string",
 *         description="The ID of the patient",
 *         example="pat_123456789"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         nullable=true,
 *         description="The ID of the clinic",
 *         example="cln_123456789"
 *     ),
 *     @OA\Property(
 *         property="reservationId",
 *         type="string",
 *         nullable=true,
 *         description="The ID of the reservation",
 *         example="res_123456789"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the record",
 *         enum={"surgery", "appointment", "inspection"}
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         nullable=true,
 *         description="Price of the service",
 *         example=1000
 *     ),
 *     @OA\Property(
 *         property="doctorsIds",
 *         type="array",
 *         nullable=true,
 *         description="Array of doctor IDs",
 *         @OA\Items(type="string", example="doc_123456789")
 *     ),
 *     @OA\Property(
 *         property="medicines",
 *         type="array",
 *         nullable=true,
 *         description="Array of medicines",
 *         @OA\Items(
 *             type="object",
 *             required={"id", "type"},
 *             @OA\Property(property="id", type="string", example="med_123456789"),
 *             @OA\Property(property="notes", type="string", nullable=true, example="Take after meals"),
 *             @OA\Property(property="type", type="string", enum={"prescription", "recommendation"})
 *         )
 *     ),
 *     @OA\Property(
 *         property="ills",
 *         type="array",
 *         nullable=true,
 *         description="Array of illnesses",
 *         @OA\Items(
 *             type="object",
 *             required={"id", "type"},
 *             @OA\Property(property="id", type="string", example="ill_123456789"),
 *             @OA\Property(property="notes", type="string", nullable=true, example="Chronic condition"),
 *             @OA\Property(property="type", type="string", enum={"diagnosis", "symptom"})
 *         )
 *     )
 * )
 */
final class CreateRecordRequest extends FormRequest
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
        return [
            'patientId' => ['required', 'string', Rule::exists('patients', 'id')],
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')],
            'reservationId' => ['nullable', 'string', Rule::exists('reservations', 'id')],
            'type' => ['required', 'string', Rule::in(array_values(RecordTypes::cases()))],
            'price' => ['nullable', 'integer' , 'min:1'],
            'doctorsIds' => ['nullable', 'array', 'min:1'],
            'doctorsIds.*' => ['required', 'string', Rule::exists('users', 'id')],

            'medicines' => ['nullable', 'array'],
            'medicines.*.id' => ['required', 'string', Rule::exists('medicines', 'id')],
            'medicines.*.notes' => ['nullable', 'string', 'max:255'],
            'medicines.*.type' => ['required' ,  'string' , Rule::in(array_values(RecordMedicinesTypes::cases()))],

            'ills' => ['nullable', 'array'],
            'ills.*.id' => ['required', 'string', Rule::exists('ills', 'id')],
            'ills.*.notes' => ['nullable', 'string', 'max:255'],
            'ills.*.type' => ['required' ,  'string' , Rule::in(array_values(RecordIllsTypes::cases()))]
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'patient_id' => $this->safe()->patientId,
            'clinic_id' => $this->safe()->clinicId ?? Auth::user()->clinic_id,
            'reservation_id' => $this->safe()->reservationId,
            'dateTime' => now()->toDateTimeString()
        ]);
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->any()) {
                    return;
                }

                $this->validateUniqueCombinations($validator, 'medicines', 'Each medicine must have a unique combination of id and type');
                $this->validateUniqueCombinations($validator, 'ills', 'Each ill must have a unique combination of id and type');
            }
        ];
    }

    private function validateUniqueCombinations(Validator $validator, string $field, string $errorMessage): void
    {
        if (empty($this->$field)) {
            return;
        }

        $combinations = array_map(
            fn($item) => $item['id'].'|'.$item['type'],
            $this->$field
        );

        if (count($combinations) !== count(array_unique($combinations))) {
            $validator->errors()->add($field, $errorMessage);
        }
    }
}
