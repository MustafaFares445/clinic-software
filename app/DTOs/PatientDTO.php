<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

/**
 * Class PatientDTO
 *
 * Data Transfer Object for Patient data.
 */
class PatientDTO implements DtoInterface
{
    protected array $arrayData;

    public function __construct(
        public ?string $firstName,
        public ?string $lastName,
        public ?string $phone = null,
        public ?string $fatherName = null,
        public ?string $motherName = null,
        public ?string $nationalNumber = null,
        public ?string $address = null,
        public ?string $notes = null,
        public ?string $birth = null,
        public ?string $gender = null,
        public ?string $clinicId = null,
        public ?UploadedFile $profileImage = null,
    ) {}

    /**
     * Create a PatientDTO instance from an array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['firstName'] ?? null,
            lastName: $data['lastName'] ?? null,
            phone: $data['phone'] ?? null,
            fatherName: $data['fatherName'] ?? null,
            motherName: $data['motherName'] ?? null,
            nationalNumber: $data['nationalNumber'] ?? null,
            address: $data['address'] ?? null,
            notes: $data['notes'] ?? null,
            birth: $data['birth'] ?? null,
            gender: $data['gender'] ?? null,
            clinicId: $data['clinicId'] ?? Auth::user()->clinic_id,
            profileImage: $data['profileImage'] ?? null
        );
    }

    /**
     * Convert the PatientDTO instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->arrayData = [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'phone' => $this->phone,
            'fatherName' => $this->fatherName,
            'motherName' => $this->motherName,
            'nationalNumber' => $this->nationalNumber,
            'address' => $this->address,
            'notes' => $this->notes,
            'birth' => $this->birth,
            'gender' => $this->gender,
            'clinic_id' => $this->clinicId,
        ];
    }
}