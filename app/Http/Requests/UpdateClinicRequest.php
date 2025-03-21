<?php

namespace App\Http\Requests;

use App\Enums\ClinicTypes;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @OA\Schema(
 *     schema="UpdateClinicRequest",
 *     @OA\Property(property="name", type="string", example="City Clinic", description="Name of the clinic"),
 *     @OA\Property(property="address", type="string", example="123 Main St", description="Physical address of the clinic"),
 *     @OA\Property(property="longitude", type="number", format="float", example=12.345678, description="Geographical longitude"),
 *     @OA\Property(property="latitude", type="number", format="float", example=98.765432, description="Geographical latitude"),
 *     @OA\Property(property="description", type="string", nullable=true, example="A modern healthcare facility", description="Optional description of the clinic"),
 *     @OA\Property(property="is_banned", type="boolean", default=false, description="Indicates if the clinic is banned"),
 *     @OA\Property(property="type", type="string", enum={"general", "specialized"}, example="general", description="Type of clinic"),
 *     @OA\Property(property="startTime", type="string", format="time", nullable=true, example="08:00", description="Opening time"),
 *     @OA\Property(property="endTime", type="string", format="time", nullable=true, example="18:00", description="Closing time"),
 *     @OA\Property(property="numberOfDoctors", type="integer", nullable=true, example=5, description="Number of doctors in the clinic"),
 *     @OA\Property(property="numberOfSecretariat", type="integer", nullable=true, example=3, description="Number of secretariat staff in the clinic"),
 *     @OA\Property(
 *         property="workingDays",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="day", type="integer", example=1, description="Day of week (0=Sunday, 6=Saturday)"),
 *             @OA\Property(property="start", type="string", format="time", example="08:00", description="Opening time for this day"),
 *             @OA\Property(property="end", type="string", format="time", example="18:00", description="Closing time for this day")
 *         )
 *     )
 * )
 */
final class UpdateClinicRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'min:2', 'max:255'],
            'address' => ['sometimes', 'string', 'max:500'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'startTime' => ['nullable', 'date_format:H:i'],
            'endTime' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string' , 'max:1000'],
            'type' => ['sometimes', 'string', Rule::in(array_values(ClinicTypes::cases()))],
            'numberOfDoctors' => ['nullable' , 'integer' , 'min:0'],
            'numberOfSecretariat' => ['nullable' , 'integer' , 'min:0'],
            'workingDays' => ['sometimes', 'array'],
            'workingDays.*.day' => ['sometimes', 'integer', 'between:0,6'],
            'workingDays.*.start' => ['sometimes', 'date_format:H:i'],
            'workingDays.*.end' => ['sometimes', 'date_format:H:i', 'after:working_days.*.start'],
        ];
    }



    /**
     * Get only the validated data that was actually provided in the request
     *
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        // Filter out values that weren't actually in the request
        return array_filter($validated, fn($value, $key) => $this->has($key), ARRAY_FILTER_USE_BOTH);
    }
}
