<?php

namespace App\Http\Requests;

use App\Enums\ClinicTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="AuthRequest",
 *     type="object",
 *     required={"firstName", "lastName", "email", "password", "username", "clinicName", "clinicAddress"},
 *     @OA\Property(property="firstName", type="string", example="John", description="First name of the user"),
 *     @OA\Property(property="lastName", type="string", example="Doe", description="Last name of the user"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Email address of the user"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Password for the user account"),
 *     @OA\Property(property="username", type="string", example="johndoe", description="Username for the user account"),
 *     @OA\Property(property="clinicName", type="string", example="Health Clinic", description="Name of the clinic"),
 *     @OA\Property(property="clinicAddress", type="string", example="123 Main St", description="Address of the clinic"),
 *     @OA\Property(property="clinicLongitude", type="string", example="40.7128", description="Longitude of the clinic location"),
 *     @OA\Property(property="clinicLatitude", type="string", example="-74.0060", description="Latitude of the clinic location"),
 *     @OA\Property(property="clinicType", type="string", enum={"hospital", "clinic", "health center"}, description="Type of the clinic"),
 *     @OA\Property(property="planId", type="integer", nullable=true, description="ID of the plan")
 * )
 */
class AuthRequest extends FormRequest
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
            'firstName' => ['required' , 'string' , 'min:3'],
            'lastName' => ['required' , 'string' , 'min:3'],
            'email' => ['required' , 'string' , 'email'],
            'password' => ['required' , 'string' , 'min:3'],
            'username' => ['required' , 'string' , 'min:3'],

            'clinicName' => ['required' , 'string' , 'min:2'],
            'clinicAddress' => ['required' , 'string'],
            'clinicLongitude' => ['nullable' , 'string'],
            'clinicLatitude'  => ['nullable' , 'string'],
            'clinicType' => ['string' , Rule::in(ClinicTypes::values())],
            'planId' => ['nullable' , 'integer' , Rule::exists('plans' , 'id')]
        ];
    }

    public function userValidated(): array
    {
        return [
            'first_name' => $this->input('firstName'),
            'last_name' => $this->input('lastName'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'username' => $this->input('username'),
        ];
    }

    public function clinicValidated(): array
    {
        return [
            'name' => $this->input('clinicName'),
            'address' => $this->input('clinicAddress'),
            'longitude' => $this->input('clinicLongitude'),
            'latitude'  => $this->input('clinicLatitude'),
            'type' => $this->input('clinicType'),
        ];
    }
}
