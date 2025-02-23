<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class TransactionRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in(['income', 'outcome'])],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'text'],
            'from' => ['required', 'string', Rule::in(['medicine', 'record', 'equipment'])],
            'finance' => ['nullable', 'boolean'],
        ];
    }
}
