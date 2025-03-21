<?php

namespace App\Http\Requests;

use App\Enums\RecordTypes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @OA\Schema(
 *     schema="RecordRequest",
 *     title="Record Request",
 *     description="Record request body for creating or updating records"
 * )
 */
final class RecordRequest extends FormRequest
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
     *
     * @OA\Property(
     *     property="patientId",
     *     type="string",
     *     description="The ID of the patient",
     *     example="pat_123456789"
     * )
     * @OA\Property(
     *     property="clinicId",
     *     type="string",
     *     nullable=true,
     *     description="The ID of the clinic",
     *     example="cln_123456789"
     * )
     * @OA\Property(
     *     property="reservationId",
     *     type="string",
     *     nullable=true,
     *     description="The ID of the reservation",
     *     example="res_123456789"
     * )
     * @OA\Property(
     *     property="description",
     *     type="string",
     *     nullable=true,
     *     description="Description of the record",
     *     example="Patient visited for regular checkup"
     * )
     * @OA\Property(
     *     property="type",
     *     type="string",
     *     description="Type of the record",
     *     enum={"surgery", "appointment", "inspection"}
     * )
     * @OA\Property(
     *     property="price",
     *     type="integer",
     *     nullable=true,
     *     description="Price of the service",
     *     example=1000
     * )
     * @OA\Property(
     *     property="doctorsIds",
     *     type="array",
     *     nullable=true,
     *     description="Array of doctor IDs",
     *
     *     @OA\Items(type="string", example="doc_123456789")
     * )
     */
    public function rules(): array
    {
        return [
            'patientId' => ['required', 'string', Rule::exists('patients', 'id')],
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')],
            'reservationId' => ['nullable', 'string', Rule::exists('reservations', 'id')],
            'description' => ['nullable', 'string' , 'min:2' , 'max:255'],
            'type' => ['required', 'string', Rule::in(array_values(RecordTypes::cases()))],
            'price' => ['nullable', 'integer' , 'min:1'],
            'doctorsIds' => ['nullable', 'array', 'min:1'],
            'doctorsIds.*' => ['required', 'string', Rule::exists('users', 'id')],
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->safe()->clinicId ?? Auth::user()->clinic_id,
            'patientId' => $this->safe()->patientId,
            'reservationId' => $this->safe()->reservationId,
        ]);
    }
}
