<?php

namespace App\Services;

use App\DTOs\PatientCreateDTO;
use App\DTOs\PatientDTO;
use App\DTOs\PatientUpdateDTO;
use App\Models\Patient;

/**
 * Service class for handling patient-related operations
 */
class PatientService
{
    /**
     * Create a new patient with associated permanent medicines and ills
     *
     * @param PatientDTO $dto Patient data including optional permanentMedicines and permanentIlls arrays
     * @return Patient The created patient instance
     */
    public function createPatient(PatientDTO $dto): Patient
    {
        $patient = Patient::query()->create($dto->toArray());

        $this->syncPermanentMedicines($patient, $dto->permanentMedicines);
        $this->syncPermanentIlls($patient, $dto->permanentIlls);

        return $patient;
    }

    /**
     * Update an existing patient and their associated permanent medicines and ills
     *
     * @param Patient $patient The patient to update
     * @param PatientDTO $dto Updated patient data including optional permanentMedicines and permanentIlls arrays
     * @return Patient The updated patient instance
     */
    public function updatePatient(Patient $patient, PatientDTO $dto): Patient
    {
        $patient->update($dto->toArray());

        $this->syncPermanentMedicines($patient, $dto->permanentMedicines);
        $this->syncPermanentIlls($patient, $dto->permanentIlls);

        return $patient;
    }

    /**
     * Sync permanent medicines for a patient
     *
     * @param Patient $patient The patient to sync medicines for
     * @param array $data Input data containing optional permanentMedicines array
     */
    protected function syncPermanentMedicines(Patient $patient, ?array $medicines): void
    {
        if ($medicines) {
            $medicinesData = collect($medicines)
                ->mapWithKeys(fn($item) => [$item['id'] => ['notes' => $item['notes'] ?? null]]);

            $patient->permanentMedicines()->sync($medicinesData);
        }
    }

    protected function syncPermanentIlls(Patient $patient, ?array $ills): void
    {
        if ($ills) {
            $illsData = collect($ills)
                ->mapWithKeys(fn($item) => [$item['id'] => ['notes' => $item['notes'] ?? null]]);

            $patient->permanentIlls()->sync($illsData);
        }
    }
}