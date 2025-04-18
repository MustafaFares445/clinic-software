<?php

namespace App\DTOs;

class UserDTO implements DtoInterface
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $username,
        public ?int $clinic_id = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password'],
            $data['username'],
            $data['clinic_id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'username' => $this->username,
            'clinic_id' => $this->clinic_id,
        ];
    }
}
