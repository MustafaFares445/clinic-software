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
 *     required={"firstName", "lastName", "email", "password", "username", "clinicName", "clinicAddress", "clinicType", "workingDays"},
 *
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         example="Mustafa",
 *         description="The first name of the user",
 *         minLength=2,
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="lastName",
 *         type="string",
 *         example="Fares",
 *         description="The last name of the user",
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
 *         description="Longitude of the clinic location",
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
 *         minimum=-90,
 *         maximum=90,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicStartTime",
 *         type="string",
 *         format="time",
 *         example="09:00",
 *         description="Clinic opening time in 24-hour format (HH:MM)",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicEndTime",
 *         type="string",
 *         format="time",
 *         example="17:00",
 *         description="Clinic closing time in 24-hour format (HH:MM)",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="clinicDescription",
 *         type="string",
 *         example="A modern healthcare facility",
 *         description="Description of the clinic",
 *         maxLength=1000,
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
 *     ),
 *     @OA\Property(
 *         property="workingDays",
 *         type="array",
 *         description="Clinic working days schedule",
 *         @OA\Items(
 *             type="object",
 *             required={"day", "start", "end"},
 *             @OA\Property(property="day", type="string", enum={"mon", "tue", "wed", "thu", "fri", "sat", "sun"}, description="Day of week", example="mon"),
 *             @OA\Property(property="start", type="string", format="time", description="Opening time in 24-hour format (HH:MM)", example="09:00"),
 *             @OA\Property(property="end", type="string", format="time", description="Closing time in 24-hour format (HH:MM)", example="17:00")
 *         )
 *     ),
 *     @OA\Property(
 *         property="planId",
 *         type="integer",
 *         description="Subscription plan ID",
 *         example=1
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
            'clinicDescription' => ['nullable', 'string' , 'max:1000'],
            'clinicType' => ['required', 'string', Rule::in(array_values(ClinicTypes::cases()))],
            'numberOfDoctors' => ['nullable' , 'integer' , 'min:0'],
            'numberOfSecretariat' => ['nullable' , 'integer' , 'min:0'],

            'workingDays' => ['required', 'array'],
            'workingDays.*.day' => ['required', 'string', Rule::in(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'])],
            'workingDays.*.start' => ['required', 'date_format:H:i'],
            'workingDays.*.end' => ['required', 'date_format:H:i', 'after:working_days.*.start'],
        ];
    }

    public function userValidated(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'username' => $this->username,
        ];
    }

    public function clinicValidated(): array
    {
        return [
            'name' => $this->clinicName,
            'address' => $this->clinicAddress,
            'longitude' => $this->clinicLongitude,
            'latitude' => $this->clinicLatitude,
            'description' => $this->clinicDescription,
            'type' => $this->clinicType,
            'number_of_doctors' => $this->numberOfDoctors,
            'number_of_secretariat' => $this->numberOfSecretariat,
            'plan_id' => $this->planId,
        ];
    }
}
