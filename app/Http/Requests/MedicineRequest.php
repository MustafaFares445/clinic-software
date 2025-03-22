<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="MedicineRequest",
 *     title="Medicine Request",
 *     description="Medicine creation/update request",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="Name of the medicine"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         description="Description of the medicine"
 *     ),
 *     @OA\Property(
 *         property="specifications",
 *         type="array",
 *         description="Array of specification IDs",
 *         @OA\Items(type="string")
 *     )
 * )
 */
class MedicineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization requirements
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'specifications' => ['sometimes', 'array'],
            'specifications.*' => ['exists:specifications,id'],
        ];
    }
}