<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="AuthRequest",
 *     title="Auth Request",
 *     description="Authentication request parameters"
 * )
 */
final class AuthRequest extends FormRequest
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
     * @OA\Property(property="fullName", type="string", minLength=2, maxLength=255, example="Mustafa Fares")
     * @OA\Property(property="email", type="string", format="email", maxLength=255, example="john.doe@example.com")
     * @OA\Property(property="password", type="string", format="password", minLength=8, maxLength=255, example="secretpass123")
     * @OA\Property(property="username", type="string", minLength=3, maxLength=255, example="johndoe")
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullName' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,username'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->input('clinicId') ?? Auth::user()->clinic_id,
        ]);
    }
}
