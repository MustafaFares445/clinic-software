<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="PatientListRequest",
 *     type="object",
 *     title="Patient List Request",
 *     description="Request parameters for filtering and sorting patient lists",
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         maxLength=255,
 *         description="Filter by patient's first name"
 *     ),
 *     @OA\Property(
 *         property="lastName",
 *         type="string",
 *         format="uuid",
 *         maxLength=255,
 *         description="Filter by patient's last name"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         maxLength=20,
 *         description="Filter by patient's phone number"
 *     ),
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         maxLength=255,
 *         description="Filter by clinic ID"
 *     ),
 *     @OA\Property(
 *         property="fullName",
 *         type="string",
 *         maxLength=255,
 *         description="Filter by patient's full name"
 *     ),
 *     @OA\Property(
 *         property="orderBy",
 *         type="string",
 *         enum={"firstName", "lastName", "nextReservation", "lastReservation", "registeredAt"},
 *         description="Field to order the results by"
 *     ),
 *     @OA\Property(
 *         property="orderType",
 *         type="string",
 *         enum={"DESC", "ASC"},
 *         description="Order type (ASC or DESC)"
 *     )
 * )
 */
class PatientListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Assuming all users are authorized to make this request.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => ['nullable', 'string', 'max:255'],
            'lastName' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'clinicId' => ['nullable', 'string', 'max:255' , Rule::exists('clinics' , 'id')],
            'fullName' => ['nullable', 'string', 'max:255'],
            'orderBy' => ['nullable', 'string', Rule::in(['firstName', 'lastName', 'nextReservation', 'lastReservation', 'registeredAt'])],
            'orderType' => ['nullable', 'string', Rule::in(['DESC', 'ASC'])],
        ];
    }
}