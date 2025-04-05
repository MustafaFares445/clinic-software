<?php

namespace App\DTOs;

class PatientDTO
{
    public function __construct(
        public ?string $firstName,
        public ?string $lastName,
        public ?string $phone = null,
        public ?float $age = null,
        public ?string $fatherName = null,
        public ?string $motherName = null,
        public ?string $nationalNumber = null,
        public ?string $address = null,
        public ?string $notes = null,
        public ?string $birth = null,
        public ?string $gender = null,
        public ?string $clinicId = null,
        public ?array $permanentMedicines = null,
        public ?array $permanentIlls = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            phone: $data['phone'] ?? null,
            age: $data['age'] ?? null,
            fatherName: $data['fatherName'] ?? null,
            motherName: $data['motherName'] ?? null,
            nationalNumber: $data['nationalNumber'] ?? null,
            address: $data['address'] ?? null,
            notes: $data['notes'] ?? null,
            birth: $data['birth'] ?? null,
            gender: $data['gender'] ?? null,
            clinicId: $data['clinicId'] ?? null,
            permanentMedicines: $data['permanentMedicines'] ?? null,
            permanentIlls: $data['permanentIlls'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'phone' => $this->phone,
            'age' => $this->age,
            'fatherName' => $this->fatherName,
            'motherName' => $this->motherName,
            'nationalNumber' => $this->nationalNumber,
            'address' => $this->address,
            'notes' => $this->notes,
            'birth' => $this->birth,
            'gender' => $this->gender,
            'clinicId' => $this->clinicId,
            'permanentMedicines' => $this->permanentMedicines,
            'permanentIlls' => $this->permanentIlls,
        ];
    }
}