<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRecordsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'startDate' => ['nullable', 'date', 'before_or_equal:endDate'],
            'endDate' => ['nullable', 'date', 'after_or_equal:startDate'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'startDate.date' => 'The start date must be a valid date.',
            'startDate.before_or_equal' => 'The start date must be before or equal to the end date.',
            'endDate.date' => 'The end date must be a valid date.',
            'endDate.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}