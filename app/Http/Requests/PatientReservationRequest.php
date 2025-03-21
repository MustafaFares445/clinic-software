<?php

namespace App\Http\Requests;

use App\Enums\ReservationTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="PatientReservationRequest",
 *     title="Patient Reservation Request",
 *     description="Request parameters for filtering patient reservations",
 *     type="object",
 *     @OA\Property(
 *         property="startDate",
 *         type="string",
 *         format="date",
 *         description="Start date for filtering (Y-m-d)",
 *         example="2023-01-01"
 *     ),
 *     @OA\Property(
 *         property="endDate",
 *         type="string",
 *         format="date",
 *         description="End date for filtering (Y-m-d), must be after or equal to startDate",
 *         example="2023-12-31"
 *     ),
 *     @OA\Property(
 *         property="doctorsIds",
 *         type="array",
 *         @OA\Items(type="integer"),
 *         description="Array of doctor IDs to filter by"
 *     ),
 *     @OA\Property(
 *         property="search",
 *         type="string",
 *         description="Search term for patient name",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="sortBy",
 *         type="string",
 *         description="Field to sort by",
 *         enum={"start", "firstName", "lastName"}
 *     ),
 *     @OA\Property(
 *         property="sortOrder",
 *         type="string",
 *         description="Sort order",
 *         enum={"asc", "desc"}
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of reservation",
 *         enum={"consultation", "follow_up", "procedure"},
 *         example="consultation"
 *     )
 * )
 */
class PatientReservationRequest extends FormRequest
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
            'startDate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'endDate' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:startDate'],
            'doctorsIds' => ['nullable', 'array'],
            'doctorsIds.*' => ['integer', 'exists:users,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'sortBy' => ['nullable', 'string', 'in:start,firstName,lastName'],
            'sortOrder' => ['nullable', 'string', 'in:asc,desc'],
            'type' => ['nullable' , 'string' , Rule::in(array_values(ReservationTypes::cases()))]
        ];
    }
}