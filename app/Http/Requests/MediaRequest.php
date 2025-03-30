<?php

namespace App\Http\Requests;

use App\Enums\MedicalMediaCollection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="MediaRequest",
 *     title="Media Upload Request",
 *     description="Request validation rules for medical media uploads",
 *     required={"upload" , "collection"},
 *     @OA\Property(
 *         property="upload",
 *         type="string",
 *         format="binary",
 *         description="Medical file to upload. Supported formats: jpeg, png, jpg, gif, mp3, wav, ogg, mp4, mov, avi, wmv, dicom, dcm, nii, nii.gz, pdf, doc, docx, xlsx, csv, xml, json. Max size: 50MB",
 *         example="example.jpg"
 *     ),
 *     @OA\Property(
 *         property="collection",
 *         type="string",
 *         description="Type of medical media collection",
 *         enum={"images", "videos", "audios", "files"},
 *         example="images"
 *     )
 * )
 */
final class MediaRequest extends FormRequest
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
     *
     * @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *         @OA\Examples(
     *             example="file_size",
     *             value={"message": "The file must not exceed 50MB"},
     *             summary="File size exceeds limit"
     *         ),
     *         @OA\Examples(
     *             example="file_type",
     *             value={"message": "The file must be one of these types: jpeg, png, jpg, gif, mp3, wav, ogg, mp4, mov, avi, wmv, dicom, dcm, nii, nii.gz, pdf, doc, docx, xlsx, csv, xml, json"},
     *             summary="Invalid file type"
     *         ),
     *         @OA\Examples(
     *             example="collection",
     *             value={"message": "The collection must be one of: images, videos, audios, files"},
     *             summary="Invalid collection type"
     *         )
     *     )
     * )
     */
    public function rules(): array
    {
        return [
            'upload' => [
                'required',
                'file',
                'max:51200', // Max 50MB
                'mimes:jpeg,png,jpg,gif,mp3,wav,ogg,mp4,mov,avi,wmv,dicom,dcm,nii,nii.gz,pdf,doc,docx,xlsx,csv,xml,json'
            ],
            'collection' => ['required', 'string', Rule::in(MedicalMediaCollection::values())],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'upload.max' => 'The file must not exceed 50MB',
            'upload.mimes' => 'The file must be one of these types: jpeg, png, jpg, gif, mp3, wav, ogg, mp4, mov, avi, wmv, dicom, dcm, nii, nii.gz, pdf, doc, docx, xlsx, csv, xml, json',
            'collection.in' => 'The collection must be one of: ' .  Rule::in(MedicalMediaCollection::values()),
        ];
    }
}
