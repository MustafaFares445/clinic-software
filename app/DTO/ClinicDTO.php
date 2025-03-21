<?php
namespace App\DTO;

class ClinicDTO
{
    public function __construct(
        public string $name,
        public string $address,
        public ?float $longitude = null,
        public ?float $latitude = null,
        public ?string $start = null,
        public ?string $end = null,
        public ?string $description = null,
        public string $type,
        public ?int $number_of_doctors = null,
        public ?int $number_of_secretariat = null,
        public ?int $plan_id = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['address'],
            $data['longitude'] ?? null,
            $data['latitude'] ?? null,
            $data['start'] ?? null,
            $data['end'] ?? null,
            $data['description'] ?? null,
            $data['type'],
            $data['number_of_doctors'] ?? null,
            $data['number_of_secretariat'] ?? null,
            $data['plan_id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'start' => $this->start,
            'end' => $this->end,
            'description' => $this->description,
            'type' => $this->type,
            'number_of_doctors' => $this->number_of_doctors,
            'number_of_secretariat' => $this->number_of_secretariat,
            'plan_id' => $this->plan_id,
        ];
    }
}