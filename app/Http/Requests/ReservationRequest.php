<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @OA\Schema(
 *     schema="ReservationRequest",
 *     type="object",
 *     required={"start", "end", "patientId", "type"},
 *     @OA\Property(
 *         property="start",
 *         type="string",
 *         format="date-time",
 *         example="2024-12-12T02:30:00Z",
 *         description="Reservation start time"
 *     ),
 *     @OA\Property(
 *         property="end",
 *         type="string",
 *         format="date-time",
 *         example="2024-12-12T03:30:00Z",
 *         description="Reservation end time"
 *     ),
 *     @OA\Property(
 *         property="patientId",
 *         type="integer",
 *         example=1,
 *         description="Patient ID"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         example="appointment",
 *         description="Reservation type",
 *         enum={"surgery", "appointment", "inspection"}
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="income",
 *         default="income",
 *         description="Reservation status",
 *         enum={"income", "check", "dismiss", "cancelled"}
 *     )
 * )
 */
class ReservationRequest extends FormRequest
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
            'start' => ['required', 'date_format:Y-m-d H:i:s'],
            'end' => ['required', 'date_format:Y-m-d H:i:s'],
            'patientId' => ['required' , 'integer' , Rule::exists('patients' , 'id')],
            'type' => ['required' , Rule::in(ReservationTypes::values())],
            'status' => ['nullable' , 'string' , Rule::in(ReservationStatuses::values())]
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default) , [
            'status' => $this->input('status' , ReservationStatuses::INCOME)
        ]);
    }
}
