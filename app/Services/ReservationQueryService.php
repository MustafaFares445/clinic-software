<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

final class ReservationQueryService
{
    private Builder $query;

    public static function make(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->query = Reservation::query();
    }

    public function filterByPatient(?string $patientId): self
    {
        if ($patientId) {
            $this->query->where('patient_id', $patientId);
        }
        return $this;
    }

    public function filterByClinic(?string $clinicId): self
    {
        if ($clinicId) {
            $this->query->where('clinic_id', $clinicId);
        }
        return $this;
    }

    public function filterByDoctors(?array $doctorsIds): self
    {
        if (!empty($doctorsIds))
            $this->query->whereIn('doctor_id', $doctorsIds);

        return $this;
    }

    public function filterByDateRange(?string $startDate, ?string $endDate): self
    {
        if ($startDate) {
            $this->query->where('start', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $this->query->where('end', '<=', Carbon::parse($endDate));
        }
        return $this;
    }

    public function filterByStatus(?string $status): self
    {
        if ($status) {
            $this->query->where('status', $status);
        }
        return $this;
    }

    public function filterByType(?string $type): self
    {
        if ($type) {
            $this->query->where('type', $type);
        }
        return $this;
    }

    public function filterByPatientName(?string $name): self
    {
        if ($name) {
            $this->query->whereHas('patient', function($query) use ($name) {
                $query->where('firstName', 'like', "%$name%")
                      ->orWhere('lastName', 'like', "%$name%");
            });
        }
        return $this;
    }

    public function withRelations(array $relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    public function sortBy(string $column, string $direction = 'asc'): self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }
}