<?php

namespace App\Services;

use App\Models\Record;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service class for building and filtering Record queries
 */
class RecordQueryService
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Record::query();
    }

    /**
     * Create a new instance of the service
     *
     * @return self
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * Filter records by patient ID
     *
     * @param string|null $patientId The patient ID to filter by
     * @return self
     */
    public function filterByPatient(?string $patientId): self
    {
        if ($patientId)
            $this->query->where('patient_id', $patientId);

        return $this;
    }

     /**
     * Filter records by type
     *
     * @param string|null $type The type to filter by
     * @return self
     */
    public function filterByType(?string $type): self
    {
        if ($type)
            $this->query->where('type', $type);

        return $this;
    }

    /**
     * Filter records by date range
     *
     * @param string|null $startDate The start date of the range
     * @param string|null $endDate The end date of the range
     * @return self
     */
    public function filterByDateRange(?string $startDate, ?string $endDate): self
    {
        if ($startDate && $endDate)
            $this->query->whereBetween('dateTime', [$startDate, $endDate]);

        return $this;
    }

    /**
     * Filter records by search term
     *
     * @param string|null $searchTerm The search term to filter by
     * @return self
     */
    public function filterBySearchTerm(?string $searchTerm): self
    {
        $this->query->where(function($query) use ($searchTerm) {
            $query->where('notes', 'like', "%{$searchTerm}%")
                ->orWhereHas('ills', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });
        });

        return $this;
    }


    // public function filterByText(?string $searchTerm): self
    // {
    //     if ($searchTerm) {
    //         // Use Scout's search functionality
    //         $this->query->whereIn('id', Record::search($searchTerm)->keys());
    //     }

    //     return $this;
    // }

    /**
     * Eager load relations with the query
     *
     * @param array $relations Array of relations to load
     * @return self
     */
    public function withRelations(array $relations): self
    {
        $this->query->with($relations);

        return $this;
    }

    /**
     * Sort the results by a specific field
     *
     * @param string $field The field to sort by
     * @param string $direction The sort direction (asc/desc)
     * @return self
     */
    public function sortBy(string $field, string $direction = 'desc'): self
    {
        if(in_array($field  , ['firstName' , 'lastName'])){
            $this->query->join('patients', 'patients.id', '=', 'reservations.patient_id')
                ->orderBy('patients.' . $field, $direction);
        }else{
            $this->query->orderBy($field, $direction);
        }

        return $this;
    }

    /**
     * Get the underlying query builder instance
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }
}