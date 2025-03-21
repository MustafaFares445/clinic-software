<?php

namespace App\Actions;

use App\DTO\UserDTO;
use App\Models\User;
use App\DTO\ClinicDTO;
use App\Models\Clinic;
use App\DTO\ClinicWorkingDayDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CreateClinicSubscription
{
    /**
     * Handles the creation of a clinic subscription.
     *
     * @param ClinicDTO $clinicData Data for creating the clinic
     * @param UserDTO $userData Data for creating the user
     * @param Collection<ClinicWorkingDayDTO> $workingDaysData Collection of working days data
     * @return User The created user with admin role
     */
    public function handle(ClinicDTO $clinicDTO, UserDTO $userDTO, Collection $workingDaysData): User
    {
        return DB::transaction(function () use ($clinicDTO, $userDTO, $workingDaysData) {

            $clinic = Clinic::create($clinicDTO->toArray());

            $user = $this->createUser($userDTO, $clinic);

            $this->addWorkingDays($clinic, $workingDaysData);

            $user->assignRole('admin');

            return $user;
        });
    }


    /**
     * Creates a new user associated with the clinic.
     *
     * @param UserDTO $data User data
     * @param Clinic $clinic The clinic to associate with
     * @return User The created user
     */
    protected function createUser(UserDTO $userDTO, Clinic $clinic): User
    {
        return User::create([
            ...$userDTO->toArray(),
            'clinic_id' => $clinic->id,
        ]);
    }

    /**
     * Adds working days to the clinic.
     *
     * @param Clinic $clinic The clinic to add working days to
     * @param Collection<ClinicWorkingDayDTO> $data Collection of working days data
     */
    protected function addWorkingDays(Clinic $clinic, Collection $data): void
    {
        $clinic->workingDays()->createMany(
            $data->map(fn (ClinicWorkingDayDTO $day) => $day->toArray())->all()
        );
    }
}