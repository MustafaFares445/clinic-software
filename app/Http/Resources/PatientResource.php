<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="PatientResource",
 *     description="Patient resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the patient",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         description="First name of the patient",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="lastName",
 *         type="string",
 *         description="Last name of the patient",
 *         example="Doe"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         ref="#/components/schemas/MediaResource",
 *         description="Avatar of the patient"
 *     ),
 *     @OA\Property(
 *         property="age",
 *         type="integer",
 *         description="Age of the patient",
 *         example=30
 *     ),
 *     @OA\Property(
 *         property="fatherName",
 *         type="string",
 *         description="Father's name of the patient",
 *         example="Robert"
 *     ),
 *     @OA\Property(
 *         property="motherName",
 *         type="string",
 *         description="Mother's name of the patient",
 *         example="Jane"
 *     ),
 *     @OA\Property(
 *         property="birth",
 *         type="string",
 *         format="date",
 *         description="Birth date of the patient",
 *         example="1993-05-15"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Phone number of the patient",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="gender",
 *         type="string",
 *         enum={"male", "female"},
 *         description="Gender of the patient",
 *         example="male"
 *     ),
 *     @OA\Property(
 *         property="notes",
 *         type="string",
 *         description="Additional notes about the patient",
 *         example="Patient has allergies"
 *     ),
 *     @OA\Property(
 *         property="nationalNumber",
 *         type="string",
 *         description="National identification number of the patient",
 *         example="123456789"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Address of the patient",
 *         example="123 Main St, Anytown, USA"
 *     ),
 *     @OA\Property(
 *         property="createdAt",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the patient record",
 *         example="2023-10-12T07:20:50.52Z"
 *     ),
 *     @OA\Property(
 *         property="nextReservation",
 *         type="string",
 *         format="date-time",
 *         description="Next reservation date for the patient",
 *         example="2023-11-15T10:00:00.00Z"
 *     ),
 *     @OA\Property(
 *         property="lastReservation",
 *         type="string",
 *         format="date-time",
 *         description="Last reservation date for the patient",
 *         example="2023-09-10T14:30:00.00Z"
 *     ),
 *     @OA\Property(
 *         property="permanentIlls",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/IllResource"),
 *         description="List of permanent illnesses associated with the patient"
 *     ),
 *
 *     @OA\Property(
 *         property="permanentMedicines",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/MedicineResource"),
 *         description="List of permanent medicines associated with the patient"
 *     ),
 *
 *     @OA\Property(
 *         property="media",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/MediaResource"),
 *         description="List of media files associated with the patient (excluding avatar)"
 *     )
 * )
 */
final class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $firstMedia = $this->getFirstMedia('profile');

        return [
            'id' => $this->when($this->id, $this->id),
            'firstName' => $this->when($this->firstName, $this->firstName),
            'lastName' => $this->when($this->lastName, $this->lastName),
            'avatar' => MediaResource::make($firstMedia),
            'age' => $this->when($this->age, $this->age),
            'fatherName' => $this->when($this->fatherName, $this->fatherName),
            'motherName' => $this->when($this->motherName, $this->motherName),
            'birth' => $this->when($this->birth, $this->birth),
            'phone' => $this->when($this->phone, $this->phone),
            'gender' => $this->when($this->gender, $this->gender),
            'notes' => $this->when($this->notes, $this->notes),
            'nationalNumber' => $this->when($this->nationalNumber, $this->nationalNumber),
            'address' => $this->when($this->address, $this->address),
            'createdAt' => $this->when($this->created_at, Carbon::parse($this->created_at)->toDateTimeString()),

            'nextReservation' => $this->when($this->next_reservation_date, Carbon::parse($this->next_reservation_date)->toDateTimeString()),
            'lastReservation' => $this->when($this->last_reservation_date, Carbon::parse($this->last_reservation_date)->toDateTimeString()),

            'permanentIlls' => IllResource::collection($this->whenLoaded('permanentIlls')),
            'permanentMedicines' => MedicineResource::collection($this->whenLoaded('permanentMedicines')),

            'media' => $this->whenLoaded('media', function () use ($firstMedia) {
                $mediaCollection = $this->media;

                if ($firstMedia) {
                    $mediaCollection = $mediaCollection->reject(function ($media) use ($firstMedia) {
                        return $media->id === $firstMedia->id;
                    });
                }

                return MediaResource::collection($mediaCollection);
            }),

        ];
    }
}
