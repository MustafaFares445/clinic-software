<?php

namespace App\Http\Requests;

use App\Enums\RecordTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecordRequest extends FormRequest
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
            'patientId' => ['required' , 'string' , Rule::exists('patients' , 'id')],
            'clinicId' => ['nullable' , 'string' , Rule::exists('clinics' , 'id')],
            'reservationId' => ['nullable' , 'string' , Rule::exists('reservations' , 'id')],
            'description' => ['nullable' , 'text'],
            'type' => ['required' , 'string' , Rule::in(RecordTypes::values())],
            'price' => ['nullable' , 'integer'],
            'doctorsIds' => ['nullable' , 'array' , 'min:1' , Rule::exists('doctors' , 'id')],
            'doctorsIds.*' => ['required' , 'string' , Rule::exists('doctors' , 'id')],
        ];
    }
}
