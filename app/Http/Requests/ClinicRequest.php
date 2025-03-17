<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ClinicRequest",
 *     required={"name", "address", "longitude", "latitude", "type"},
 *     @OA\Property(property="name", type="string", example="City Clinic", description="Name of the clinic"),
 *     @OA\Property(property="address", type="string", example="123 Main St", description="Physical address of the clinic"),
 *     @OA\Property(property="longitude", type="number", format="float", example=12.345678, description="Geographical longitude"),
 *     @OA\Property(property="latitude", type="number", format="float", example=98.765432, description="Geographical latitude"),
 *     @OA\Property(property="description", type="string", nullable=true, example="A modern healthcare facility", description="Optional description of the clinic"),
 *     @OA\Property(property="is_banned", type="boolean", default=false, description="Indicates if the clinic is banned"),
 *     @OA\Property(property="type", type="string", enum={"general", "specialized"}, example="general", description="Type of clinic"),
 *     @OA\Property(property="start", type="string", format="time", nullable=true, example="08:00", description="Opening time"),
 *     @OA\Property(property="end", type="string", format="time", nullable=true, example="18:00", description="Closing time")
 * )
 */
final class ClinicRequest extends FormRequest
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
            'name',
            'address',
            'longitude',
            'latitude',
            'description',
            'is_banned',
            'type',
            'start',
            'end',
        ];
    }
}
