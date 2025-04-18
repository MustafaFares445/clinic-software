<?php
namespace App\DTOs;

class ClinicWorkingDayDTO implements DtoInterface
{
    public function __construct(
        public string $day,
        public string $start,
        public string $end
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['day'],
            $data['start'],
            $data['end']
        );
    }


    public function toArray(): array
    {
        return [
            'day' => $this->day,
            'start' => $this->start,
            'end' => $this->end,
        ];
    }
}
