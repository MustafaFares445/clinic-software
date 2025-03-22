<?php

namespace App\Http\Requests;

use App\Enums\RecordTypes;
use App\Enums\RecordIllsTypes;
use Illuminate\Validation\Rule;
use App\Enums\RecordMedicinesTypes;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateRecordRequest",
 *     title="Update Record Request",
 *     description="Record request body for updating records",
 *     @OA\Property(
 *         property="patientId",
 *         type="string",
 *         description="ID of the patient",
 *         example="pat_123456789"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         nullable=true,
 *         description="ID of the clinic",
 *         example="cli_123456789"
 *     ),
 *     @OA\Property(
 *         property="reservationId",
 *         type="string",
 *         nullable=true,
 *         description="ID of the reservation",
 *         example="res_123456789"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         description="Description of the record",
 *         example="Regular checkup"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         enum={"consultation", "examination", "treatment"},
 *         description="Type of the record"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         nullable=true,
 *         description="Price of the record",
 *         example=5000
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
 *             @OA\Property(property="id", type="string", example="ill_123456789"),
 *             @OA\Property(property="notes", type="string", nullable=true, example="Chronic condition"),
 *             @OA\Property(property="type", type="string", enum={"diagnosis", "symptom"})
 *         )
 *     )
 * )
 */
final class UpdateRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patientId' => ['sometimes', 'string', Rule::exists('patients', 'id')],
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')],
            'reservationId' => ['nullable', 'string', Rule::exists('reservations', 'id')],
            'description' => ['nullable', 'string', 'min:2', 'max:255'],
            'type' => ['sometimes', 'string', Rule::in(array_values(RecordTypes::cases()))],
            'price' => ['nullable', 'integer', 'min:1'],
            'doctorsIds' => ['nullable', 'array', 'min:1'],
            'doctorsIds.*' => ['sometimes', 'string', Rule::exists('users', 'id')],

            'medicines' => ['nullable', 'array'],
            'medicines.*.id' => ['sometimes', 'string', Rule::exists('medicines', 'id')],
            'medicines.*.notes' => ['nullable', 'string', 'max:255'],
            'medicines.*.type' => ['sometimes', 'string', Rule::in(array_values(RecordMedicinesTypes::cases()))],

            'ills' => ['nullable', 'array'],
            'ills.*.id' => ['sometimes', 'string', Rule::exists('ills', 'id')],
            'ills.*.notes' => ['nullable', 'string', 'max:255'],
            'ills.*.type' => ['sometimes', 'string', Rule::in(array_values(RecordIllsTypes::cases()))]
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (isset($validated['patientId'])) {
            $validated['patient_id'] = $validated['patientId'];
            unset($validated['patientId']);
        }

        if (isset($validated['clinicId'])) {
            $validated['clinic_id'] = $validated['clinicId'];
            unset($validated['clinicId']);
        }

        if (isset($validated['reservationId'])) {
            $validated['reservation_id'] = $validated['reservationId'];
            unset($validated['reservationId']);
        }

        return $validated;
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