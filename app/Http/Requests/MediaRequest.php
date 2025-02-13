<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="MediaRequest",
 *     title="Media Upload Request",
 *     description="Request validation rules for various types of medical media uploads"
 * )
 */
class MediaRequest extends FormRequest
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
     * @OA\Property(
     *     property="files",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="General media files (max 10 files, 50MB each)",
     *     maxItems=10
     * )
     * @OA\Property(
     *     property="images",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Image files (jpeg, png, jpg, gif - max 20 files, 5MB each)",
     *     maxItems=20
     * )
     * @OA\Property(
     *     property="audios",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Audio files (mp3, wav, ogg - max 5 files, 10MB each)",
     *     maxItems=5
     * )
     * @OA\Property(
     *     property="videos",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Video files (mp4, mov, avi, wmv - max 3 files, 50MB each)",
     *     maxItems=3
     * )
     * @OA\Property(
     *     property="x-ray",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="X-ray files (dicom, dcm, jpeg, png - max 10 files, 20MB each)",
     *     maxItems=10
     * )
     * @OA\Property(
     *     property="mri-scans",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="MRI scan files (dicom, dcm, nii, nii.gz - max 15 files, 50MB each)",
     *     maxItems=15
     * )
     * @OA\Property(
     *     property="ct-scans",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="CT scan files (dicom, dcm, nii, nii.gz - max 15 files, 50MB each)",
     *     maxItems=15
     * )
     * @OA\Property(
     *     property="ultrasound",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Ultrasound files (dicom, dcm, mp4, avi - max 10 files, 30MB each)",
     *     maxItems=10
     * )
     * @OA\Property(
     *     property="tests",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Test documents (pdf, doc, docx - max 15 files, 5MB each)",
     *     maxItems=15
     * )
     * @OA\Property(
     *     property="lab-reports",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Lab reports (pdf, doc, docx, xlsx, csv - max 20 files, 10MB each)",
     *     maxItems=20
     * )
     * @OA\Property(
     *     property="prescriptions",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Prescriptions (pdf, jpg, png - max 10 files, 2MB each)",
     *     maxItems=10
     * )
     * @OA\Property(
     *     property="medical-reports",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Medical reports (pdf, doc, docx - max 20 files, 15MB each)",
     *     maxItems=20
     * )
     * @OA\Property(
     *     property="patient-history",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="Patient history files (pdf, doc, docx - max 5 files, 20MB each)",
     *     maxItems=5
     * )
     * @OA\Property(
     *     property="ecg-records",
     *     type="array",
     *     @OA\Items(type="string", format="binary"),
     *     description="ECG records (pdf, dcm, xml, json - max 10 files, 10MB each)",
     *     maxItems=10
     * )
     */
    public function rules(): array
    {
        return [
            // General media files
            'files.*' => 'required|file|max:51200', // Max 50MB
            'files' => 'array|max:10', // Maximum 10 files

            // Image files
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'images' => 'array|max:20', // Maximum 20 images

            // Audio files
            'audios.*' => 'required|mimes:mp3,wav,ogg|max:10240', // Max 10MB
            'audios' => 'array|max:5', // Maximum 5 audio files

            // Video files
            'videos.*' => 'required|mimes:mp4,mov,avi,wmv|max:51200', // Max 50MB
            'videos' => 'array|max:3', // Maximum 3 video files

            // Medical Imaging
            'x-ray.*' => 'required|mimes:dicom,dcm,jpeg,png|max:20480', // Max 20MB
            'x-ray' => 'array|max:10', // Maximum 10 x-ray files

            'mri-scans.*' => 'required|mimes:dicom,dcm,nii,nii.gz|max:51200', // Max 50MB
            'mri-scans' => 'array|max:15', // Maximum 15 MRI files

            'ct-scans.*' => 'required|mimes:dicom,dcm,nii,nii.gz|max:51200', // Max 50MB
            'ct-scans' => 'array|max:15', // Maximum 15 CT scan files

            'ultrasound.*' => 'required|mimes:dicom,dcm,mp4,avi|max:30720', // Max 30MB
            'ultrasound' => 'array|max:10', // Maximum 10 ultrasound files

            // Medical Documents
            'tests.*' => 'required|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'tests' => 'array|max:15', // Maximum 15 test files

            'lab-reports.*' => 'required|mimes:pdf,doc,docx,xlsx,csv|max:10240', // Max 10MB
            'lab-reports' => 'array|max:20', // Maximum 20 lab result files

            'prescriptions.*' => 'required|mimes:pdf,jpg,png|max:2048', // Max 2MB
            'prescriptions' => 'array|max:10', // Maximum 10 prescription files

            'medical-reports.*' => 'required|mimes:pdf,doc,docx|max:15360', // Max 15MB
            'medical-reports' => 'array|max:20', // Maximum 20 medical report files

            'patient-history.*' => 'required|mimes:pdf,doc,docx|max:20480', // Max 20MB
            'patient-history' => 'array|max:5', // Maximum 5 patient history files

            // ECG/EKG Records
            'ecg-records.*' => 'required|mimes:pdf,dcm,xml,json|max:10240', // Max 10MB
            'ecg-records' => 'array|max:10', // Maximum 10 ECG files
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
            'files.*.max' => 'Each file must not exceed 50MB',
            'images.*.max' => 'Each image must not exceed 5MB',
            'audios.*.max' => 'Each audio file must not exceed 10MB',
            'videos.*.max' => 'Each video must not exceed 50MB',
            'x-ray.*.max' => 'Each x-ray file must not exceed 20MB',
            'mri-scans.*.max' => 'Each MRI scan must not exceed 50MB',
            'ct-scans.*.max' => 'Each CT scan must not exceed 50MB',
            'ultrasound.*.max' => 'Each ultrasound file must not exceed 30MB',
            'tests.*.max' => 'Each test document must not exceed 5MB',
            'lab-results.*.max' => 'Each lab result must not exceed 10MB',
            'prescriptions.*.max' => 'Each prescription must not exceed 2MB',
            'medical-reports.*.max' => 'Each medical report must not exceed 15MB',
            'patient-history.*.max' => 'Each patient history file must not exceed 20MB',
            'ecg-records.*.max' => 'Each ECG record must not exceed 10MB',
            '*.*.mimes' => 'The file must be of type: :values',
        ];
    }

}
