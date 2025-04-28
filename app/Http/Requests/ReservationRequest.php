<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Actions\CheckReservationConflict;

/**
 * @OA\Schema(
 *     schema="ReservationRequest",
 *     type="object",
 *
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
 *         format="uuid",
 *         example="550e8400-e29b-41d4-a716-446655440000",
 *         description="Patient UUID"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         example="surgery",
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
 *         format="uuid",
 *         example="550e8400-e29b-41d4-a716-446655440001",
 *         description="Doctor UUID"
 *     ),
 *     @OA\Property(
 *         property="specificationId",
 *         type="string",
 *         format="uuid",
 *         example="550e8400-e29b-41d4-a716-446655440002",
 *         description="Specification UUID"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         format="uuid",
 *         example="550e8400-e29b-41d4-a716-446655440003",
 *         description="Clinic UUID"
 *     )
 * )
 */
final class ReservationRequest extends FormRequest
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
        $rules = [];

        if ($this->has('start')) {
            $rules['start'] = ['date_format:Y-m-d H:i:s', 'after_or_equal:'.now()->subMinute()];
        }

        if ($this->has('end')) {
            $rules['end'] = ['date_format:Y-m-d H:i:s', 'after:start'];
        }

        if ($this->has('patientId')) {
            $rules['patientId'] = ['uuid', Rule::exists('patients', 'id')];
        }

        if ($this->has('type')) {
            $rules['type'] = [Rule::in(array_values(ReservationTypes::cases()))];
        }

        if ($this->has('status')) {
            $rules['status'] = ['string', Rule::in(array_values(ReservationStatuses::cases()))];
        }

        if ($this->has('doctorId')) {
            $rules['doctorId'] = ['uuid', Rule::exists('users', 'id')];
        }

        if ($this->has('specificationId')) {
            $rules['specificationId'] = ['uuid', Rule::exists('specifications', 'id')];
        }

        if ($this->has('clinicId')) {
            $rules['clinicId'] = ['uuid', Rule::exists('clinics', 'id')];
        }

        if ($this->isMethod('POST')) {
            $rules['start'] = ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:now'];
            $rules['end'] = ['required', 'date_format:Y-m-d H:i:s', 'after:start'];
            $rules['type'] = ['required', Rule::in(array_values(ReservationTypes::cases()))];
            $rules['patientId'] = ['required', 'uuid', Rule::exists('patients', 'id')];
        }

        if (isset($rules['start'])) {
            $rules['start'][] = [$this, 'validateTimeConflict'];
        }

        return $rules;
    }

    private function validateTimeConflict($attribute, $value, $fail)
    {
        $conflictExists = app(CheckReservationConflict::class)->handle(
            $this->input('clinicId'),
            $this->input('start'),
            $this->input('end'),
            $this->route('reservation')
        );

        if ($conflictExists) {
            $fail('There is already a reservation in this time slot for the selected clinic and doctor.');
        }
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Transform camelCase to snake_case keys
        $transformedData = [];

        if ($this->has('patientId')) {
            $transformedData['patient_id'] = $this->input('patientId');
        }

        if ($this->has('doctorId')) {
            $transformedData['doctor_id'] = $this->input('doctorId');
        }

        if ($this->has('specificationId')) {
            $transformedData['specification_id'] = $this->input('specificationId');
        }

        if ($this->has('clinicId')) {
            $transformedData['clinic_id'] = $this->input('clinicId');
        }

        // Set default status for new reservations
        if ($this->isMethod('POST')) {
            $transformedData['status'] = $this->input('status', ReservationStatuses::INCOME);
        } elseif ($this->has('status')) {
            $transformedData['status'] = $this->input('status');
        }

        // Keep the original validated data that doesn't need transformation
        $keepOriginal = ['start', 'end', 'type'];
        foreach ($keepOriginal as $field) {
            if (isset($validated[$field])) {
                $transformedData[$field] = $validated[$field];
            }
        }

        return $transformedData;
    }
}
