<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
 *         type="string",
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
 *     ),
 *     @OA\Property(
 *         property="doctorId",
 *         type="string",
 *         example=2,
 *         description="Doctor ID"
 *     ),
 *     @OA\Property(
 *          property="specificationId",
 *          type="string",
 *          example=2,
 *          description="Specification ID"
 *      ),
 *     @OA\Property(
 *          property="clinicId",
 *          type="string",
 *          example=3,
 *          description="Clinic ID"
 *      )
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
            'start' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:now'],
            'end' => ['required', 'date_format:Y-m-d H:i:s', 'after:start'],
            'patientId' => ['required', 'string', Rule::exists('patients', 'id')],
            'type' => ['required', Rule::in(array_values(ReservationTypes::cases()))],
            'status' => ['nullable', 'string', Rule::in(array_values(ReservationStatuses::cases()))],
            'doctorId' => ['nullable', 'string', Rule::exists('users', 'id')],
            'specificationId' => ['nullable', 'string', Rule::exists('specifications', 'id')],
            'clinicId' => ['nullable', 'string', Rule::exists('clinics', 'id')]
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default) , [
            'status' => $this->input('status' , ReservationStatuses::INCOME),
            'patient_id' => $this->input('patientId'),
            'doctor_id' => $this->input('doctorId'),
            'specification_id' => $this->input('specificationId'),
            'clinic_id' => $this->input('clinicId' , Auth::user()->clinic_id),
            'doctor_id' => Auth::user()->hasRole('doctor') ? Auth::id() : $this->input('doctorId' , Auth::id())
        ]);
    }
}
