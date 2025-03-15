<?php

namespace App\Http\Requests;

use App\Enums\ClinicTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="ClinicSubscriptionRequest",
 *     type="object",
 *     required={"fullName", "email", "password", "username", "clinicName", "clinicAddress", "clinicType"},
 *
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         example="Mustafa",
 *         description="The name of the user",
 *         minLength=2,
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="lastName",
 *         type="string",
 *         example="Fares",
 *         description="The name of the user",
 *         minLength=2,
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="john.doe@example.com",
 *         description="Email address of the user (must be unique)",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         example="password123",
 *         description="Password for the user account",
 *         minLength=8,
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="username",
 *         type="string",
 *         example="johndoe",
 *         description="Username for the user account (must be unique, allows letters, numbers, dashes and underscores)",
 *         minLength=3,
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="clinicName",
 *         type="string",
 *         example="Health Clinic",
 *         description="Name of the clinic",
 *         minLength=2,
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="clinicAddress",
 *         type="string",
 *         example="123 Main St",
 *         description="Address of the clinic",
 *         maxLength=500
 *     ),
 *     @OA\Property(
 *         property="clinicLongitude",
 *         type="number",
 *         format="float",
 *         example="40.7128",
 *         description="Longitude of the clinic location (-180 to 180)",
 *         minimum=-180,
 *         maximum=180,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicLatitude",
 *         type="number",
 *         format="float",
 *         example="-74.0060",
 *         description="Latitude of the clinic location",
 *         maxLength=255,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicStartTime",
 *         type="string",
 *         format="time",
 *         example="09:00",
 *         description="Clinic opening time",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicEndTime",
 *         type="string",
 *         format="time",
 *         example="17:00",
 *         description="Clinic closing time",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicDescription",
 *         type="string",
 *         example="A modern healthcare facility",
 *         description="Description of the clinic",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicType",
 *         type="string",
 *         enum={"hospital", "clinic", "health_center"},
 *         description="Type of the clinic"
 *     ),
 *     @OA\Property(
 *         property="numberOfDoctors",
 *         type="integer",
 *         example=5,
 *         description="Number of doctors in the clinic",
 *         nullable=true,
 *         minimum=0
 *     ),
 *     @OA\Property(
 *         property="numberOfSecretariat",
 *         type="integer",
 *         example=2,
 *         description="Number of secretariat staff in the clinic",
 *         nullable=true,
 *         minimum=0
 *     )
 * )
 */
final class ClinicSubscriptionRequest extends FormRequest
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
            'firstName' => ['required', 'string', 'min:2', 'max:255'],
            'lastName' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,username'],

            'clinicName' => ['required', 'string', 'min:2', 'max:255'],
            'clinicAddress' => ['required', 'string', 'max:500'],
            'clinicLongitude' => ['nullable', 'numeric', 'between:-180,180'],
            'clinicLatitude' => ['nullable', 'numeric', 'between:-90,90'],
            'clinicStartTime' => ['nullable', 'date_format:H:i'],
            'clinicEndTime' => ['nullable', 'date_format:H:i'],
            'clinicDescription' => ['nullable', 'string' , 'max:1000'],
            'clinicType' => ['required', 'string', Rule::in(array_values(ClinicTypes::cases()))],
            'numberOfDoctors' => ['nullable' , 'integer' , 'min:0'],
            'numberOfSecretariat' => ['nullable' , 'integer' , 'min:0'],
        ];
    }

    public function userValidated(): array
    {
        return $this->safe()->only([
            'firstName',
            'lastName',
            'email',
            'password',
            'username',
        ]);
    }

    public function clinicValidated(): array
    {
        return [
            'name' => $this->safe()->clinicName,
            'address' => $this->safe()->clinicAddress,
            'longitude' => $this->safe()->clinicLongitude,
            'latitude' => $this->safe()->clinicLatitude,
            'start' => $this->safe()->clinicStartTime,
            'end' => $this->safe()->clinicEndTime,
            'description' => $this->safe()->clinicDescription,
            'type' => $this->safe()->clinicType,
            'number_of_doctors' => $this->safe()->numberOfDoctors,
            'number_of_secretariat' => $this->safe()->numberOfSecretariat,
            'plan_id' => $this->safe()->planId,
        ];
    }
}
