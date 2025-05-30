<?php

namespace App\Services;

use App\DTOs\PatientDTO;
use App\Models\Patient;
use App\Traits\HandlesMedia;
use Illuminate\Support\Facades\DB;

/**
 * Service class for handling patient-related operations
 */
class PatientService
{
    use HandlesMedia;

    /**
     * Create a new patient with associated permanent medicines and ills
     *
     * @param PatientDTO $dto Patient data including optional permanentMedicines and permanentIlls arrays
     * @return Patient The created patient instance
     */
    public function createPatient(PatientDTO $dto): Patient
    {
        return DB::transaction(function() use ($dto){
            $patient = Patient::query()->create($dto->toArray());

            if($dto->profileImage)
                $this->handleMediaUpload($dto->profileImage , $patient , 'profiles');

            return $patient;
        });
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
        return DB::transaction(function () use ($patient , $dto){
            $patient->update($dto->toArray());

            if($dto->profileImage)
                $this->handleMediaUpdate($dto->profileImage , $patient , 'profiles');

            return $patient;
        });
    }
}