<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

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

        /**
         * @var array|null $permanentMedicines Array of permanent medicines in format:
         * [
         *     ['id' => string, 'notes' => string],
         *     ...
         * ]
         */
        public ?array $permanentMedicines = null,

        /**
         * @var array|null $permanentIlls Array of permanent illnesses in format:
         * [
         *     ['id' => string, 'notes' => string],
         *     ...
         * ]
         */
        public ?array $permanentIlls = null,

    ) {}

    /**
     * Creates a new PatientDTO instance from an array of data
     *
     * @param array $data Associative array containing patient data with keys:
     *                    - firstName: string|null
     *                    - lastName: string|null
     *                    - phone: string|null
     *                    - fatherName: string|null
     *                    - motherName: string|null
     *                    - nationalNumber: string|null
     *                    - address: string|null
     *                    - notes: string|null
     *                    - birth: string|null
     *                    - gender: string|null
     *                    - clinicId: string|null (defaults to authenticated user's clinic ID)
     *                    - permanentMedicines: array|null
     *                    - permanentIlls: array|null
     *                    - profileImage: UploadedFile|null
     * @return self New PatientDTO instance
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
            permanentMedicines: $data['permanentMedicines'] ?? null,
            permanentIlls: $data['permanentIlls'] ?? null,
            profileImage: $data['profileImage'] ?? null
        );
    }

    /**
     * Converts the PatientDTO instance to an array
     *
     * @return array Associative array containing:
     *               - firstName: string|null
     *               - lastName: string|null
     *               - phone: string|null
     *               - fatherName: string|null
     *               - motherName: string|null
     *               - nationalNumber: string|null
     *               - address: string|null
     *               - notes: string|null
     *               - birth: string|null
     *               - gender: string|null
     *               - clinic_id: string|null
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

    /**
     * Adds permanent medicines to the array data
     *
     * @return self Returns the current instance for method chaining
     */
    public function withPermanentMedicines(): self
    {
        $this->arrayData = array_merge($this->arrayData, [
            'permanentMedicines' => $this->permanentMedicines,
        ]);

        return $this;
    }

    /**
     * Adds permanent illnesses to the array data
     *
     * @return self Returns the current instance for method chaining
     */
    public function withPermanentIlls(): self
    {
        $this->arrayData = array_merge($this->arrayData, [
            'permanentIlls' => $this->permanentIlls,
        ]);

        return $this;
    }
}