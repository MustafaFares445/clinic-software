<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="PatientIndexRequest",
 *     type="object",
 *     title="Patient Index Request",
 *     description="Request parameters for indexing patients",
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         nullable=true,
 *         description="The ID of the clinic. Must exist in the clinics table."
 *     ),
 *     @OA\Property(
 *         property="orderBy",
 *         type="string",
 *         nullable=true,
 *         description="The field to order by. Allowed values: firstName, lastName, nextReservation, lastReservation, registeredAt.",
 *         enum={"firstName", "lastName", "nextReservation", "lastReservation", "registeredAt"}
 *     ),
 *     @OA\Property(
 *         property="orderType",
 *         type="string",
 *         nullable=true,
 *         description="The order type. Allowed values: DESC, ASC.",
 *         enum={"DESC", "ASC"}
 *     )
 * )
 */
class PatientIndexRequest extends FormRequest
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
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')],
            'orderBy' => ['nullable', 'string', Rule::in(['firstName', 'lastName', 'nextReservation', 'lastReservation', 'registeredAt'])],
            'orderType' => ['nullable', 'string', Rule::in(['DESC', 'ASC'])]
        ];
    }
}
