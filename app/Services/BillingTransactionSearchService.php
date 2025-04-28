<?php

namespace App\Services;

use App\Models\BillingTransaction;
use Illuminate\Database\Eloquent\Builder;

class BillingTransactionSearchService
{
    protected Builder $query;

    public static function make(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->query = BillingTransaction::query();
    }


    public function filterByType(?string $type): self
    {
        if ($type) {
            $this->query->where('type', $type);
        }

        return $this;
    }

    public function filterByYear(?int $year): self
    {
        if ($year) {
            $this->query->whereYear('created_at', $year);
        }

        return $this;
    }

    public function filterByPatient(?string $patientName): self
    {
        if ($patientName) {
            $searchTerm = "%{$patientName}%";
            $this->query->whereHas('patient', function($q) use ($searchTerm) {
                $q->whereRaw("CONCAT_WS(' ', firstName, lastName) LIKE ?", [$searchTerm]);
            });
        }

        return $this;
    }

    public function filterByDuration(?string $startDate , ?string $endDate): self
    {
        if($startDate && $endDate){
            $this->query->whereBetween('created_at' , [$startDate , $endDate]);
        }

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
