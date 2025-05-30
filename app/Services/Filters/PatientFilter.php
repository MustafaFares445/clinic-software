<?php

namespace App\Services\Filters;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PatientFilter
 *
 * This class is responsible for filtering patients based on various criteria.
 */
class PatientFilter
{
    protected Builder $query;

    public function __construct()
    {
        $this->query = Patient::query();
    }

    /**
     * Set the base query for filtering.
     *
     * @param Builder $query
     * @return self
     */
    public static function make(Builder $query): self
    {
        $instance = new self();

        $instance->query = $query;

        return $instance;
    }

    // ... existing code ...

    /**
     * Apply all filters based on the provided criteria.
     *
     * @param array $filters
     * @return self
     */
    public function applyFilters(array $filters): self
    {
        if (isset($filters['firstName'])) {
            $this->byFirstName($filters['firstName']);
        }

        if (isset($filters['lastName'])) {
            $this->byLastName($filters['lastName']);
        }

        if (isset($filters['phone'])) {
            $this->byPhone($filters['phone']);
        }

        if (isset($filters['clinicId'])) {
            $this->byClinicId($filters['clinicId']);
        }

        if (isset($filters['fullName'])) {
            $this->byFullName($filters['fullName']);
        }

        return $this;
    }

// ... existing code ...

    /**
     * Filter patients by first name.
     *
     * @param string|null $firstName
     * @return self
     */
    public function byFirstName(?string $firstName = null): self
    {
        if ($firstName)
            $this->query->where('firstName', 'like', "%$firstName%");

        return $this;
    }

    /**
     * Filter patients by last name.
     *
     * @param string|null $lastName
     * @return self
     */
    public function byLastName(?string $lastName = null): self
    {
        if (!empty($lastName))
            $this->query->where('lastName', 'like', "%$lastName%");

        return $this;
    }

    /**
     * Filter patients by phone number.
     *
     * @param string|null $phone
     * @return self
     */
    public function byPhone(?string $phone = null): self
    {
        if ($phone)
            $this->query->where('phone', 'like', "%$phone%");

        return $this;
    }

    /**
     * Filter patients by clinic ID.
     *
     * @param string|null $clinicId
     * @return self
     */
    public function byClinicId(?string $clinicId = null): self
    {
        if ($clinicId)
            $this->query->where('clinic_id', $clinicId);

        return $this;
    }

    /**
     * Filter patients by full name (first name or last name).
     *
     * @param string|null $fullName
     * @return self
     */
    public function byFullName(?string $fullName = null): self
    {
        if ($fullName) {
            $this->query->where(function ($query) use ($fullName) {
                $query->where('firstName', 'like', "%$fullName%")
                      ->orWhere('lastName', 'like', "%$fullName%");
            });
        }

        return $this;
    }

    /**
     * Get the filtered query.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }
}