<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use Illuminate\Validation\Rule;
use App\Rules\ReservationConflictRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CheckReservationConflictRequest",
 *     required={"start", "end"},
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         description="The ID of the clinic",
 *         example="123e4567-e89b-12d3-a456-426614174000"
 *     ),
 *     @OA\Property(
 *         property="start",
 *         type="string",
 *         format="date-time",
 *         description="The start time of the reservation",
 *         example="2023-10-01T09:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="end",
 *         type="string",
 *         format="date-time",
 *         description="The end time of the reservation",
 *         example="2023-10-01T10:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="reservationId",
 *         type="string",
 *         description="The ID of the reservation (nullable for new reservations)",
 *         example="123e4567-e89b-12d3-a456-426614174000"
 *     )
 * )
 */
class CheckReservationConflictRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'clinicId' => ['nullable' , 'uuid' , Rule::exists(Clinic::class, 'id')],
            'reservationId' => ['nullable' , 'uuid' , Rule::exists('reservations' , 'id')],
            'start' => [
                'required' , 'date' ,
                'date_format:Y-m-d H:i:s',
                'after_or_equal:'.now()->subMinute(),
                new ReservationConflictRule($this->input('clinicId'), $this->route('reservationId'))
            ],
            'end' => ['required' , 'date' ,'date_format:Y-m-d H:i:s', 'after:start'],
        ];
    }
}