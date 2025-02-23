<?php

namespace Tests\Feature;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ReservationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    private Clinic $clinic;

    private User $doctor;

    private Patient $patient;

    private Specification $specification;

    protected function setUp(): void
    {
        parent::setUp();

        // Create base test data
        $this->clinic = Clinic::factory()->create([
            'start' => '09:00',
            'end' => '17:00',
        ]);

        $this->user = User::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        // Create a doctor user
        $this->doctor = User::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);
        $this->doctor->assignRole('doctor');

        $this->patient = Patient::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $this->specification = Specification::factory()->create();

        /** @var Authenticatable $user */
        $user = $this->user;
        $this->actingAs($user);
    }

    public function testCanListReservations()
    {
        Reservation::factory()->count(3)->create([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'specification_id' => $this->specification->id,
            'start' => now()->startOfWeek(),
            'end' => now()->startOfWeek()->addHour(),
        ]);

        $response = $this->getJson('/api/reservations');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'start',
                        'end',
                        'status',
                        'patient',
                        'doctor',
                        'specification',
                    ],
                ],
            ]);
    }

    public function testCanFilterReservationsByDateRange()
    {
        // Create reservations with different dates
        $pastReservation = Reservation::factory()->create([
            'start' => now()->subWeek(),
            'end' => now()->subWeek()->addHour(),
        ]);

        $currentReservation = Reservation::factory()->create([
            'start' => now(),
            'end' => now()->addHour(),
        ]);

        $response = $this->getJson('/api/reservations?' . http_build_query([
            'start' => now()->startOfDay()->format('Y-m-d'),
            'end' => now()->endOfDay()->format('Y-m-d'),
        ]));

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function testCanStoreNewReservation()
    {
        $reservationData = [
            'patientId' => $this->patient->id,
            'doctorId' => $this->doctor->id,
            'specificationId' => $this->specification->id,
            'start' => now()->addDay()->format('Y-m-d H:i:s'),
            'end' => now()->addDay()->addHour()->format('Y-m-d H:i:s'),
            'status' => ReservationStatuses::INCOME,
            'clinicId' => $this->clinic->id,
            'type' => ReservationTypes::APPOINTMENT,
        ];

        $response = $this->postJson('/api/reservations', $reservationData);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'start',
                    'end',
                    'status',
                    'patient',
                    'doctor',
                    'specification',
                ],
            ]);

        $this->assertDatabaseHas('reservations', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'specification_id' => $this->specification->id,
        ]);
    }

    public function testCanShowReservation()
    {
        $reservation = Reservation::factory()->create([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'specification_id' => $this->specification->id,
        ]);

        $response = $this->getJson("/api/reservations/{$reservation->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'start',
                    'end',
                    'status',
                    'patient',
                    'doctor',
                    'specification',
                ],
            ]);
    }

    public function testCanUpdateReservation()
    {
        $reservation = Reservation::factory()->create([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'specification_id' => $this->specification->id,
            'type' => ReservationTypes::INSPECTION,
        ]);

        $updateData = [
            'patientId' => $this->patient->id,
            'doctorId' => $this->doctor->id,
            'specificationId' => $this->specification->id,
            'start' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end' => now()->addDays(2)->addHour()->format('Y-m-d H:i:s'),
            'status' => ReservationStatuses::INCOME,
            'clinicId' => $this->clinic->id,
            'type' => ReservationTypes::SURGERY,
        ];

        $response = $this->putJson("/api/reservations/{$reservation->id}", $updateData);

        $response->assertOk();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'start' => $updateData['start'],
            'end' => $updateData['end'],
        ]);
    }

    public function testCanChangeReservationStatus()
    {
        $reservation = Reservation::factory()->create([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'specification_id' => $this->specification->id,
            'status' => ReservationStatuses::INCOME,
        ]);

        $response = $this->patchJson("/api/reservations/{$reservation->id}/change-status", [
            'status' => ReservationStatuses::CHECK,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => ReservationStatuses::CHECK,
        ]);
    }

    public function testCanDeleteReservation()
    {
        $reservation = Reservation::factory()->create([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'specification_id' => $this->specification->id,
        ]);

        $response = $this->deleteJson("/api/reservations/{$reservation->id}");

        $response->assertOk();

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);
    }

    public function testValidatesRequiredFieldsWhenStoringReservation()
    {
        $response = $this->postJson('/api/reservations', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['patientId', 'start', 'end', 'type']);
    }

    public function testValidatesDateRangeWhenStoringReservation()
    {
        $reservationData = [
            'patientId' => $this->patient->id,
            'doctorId' => $this->doctor->id,
            'specificationId' => $this->specification->id,
            'start' => now()->addHour()->format('Y-m-d H:i:s'),
            'end' => now()->format('Y-m-d H:i:s'), // End before start
            'status' => ReservationStatuses::INCOME,
            'clinicId' => $this->clinic->id,
            'type' => ReservationTypes::APPOINTMENT,
        ];

        $response = $this->postJson('/api/reservations', $reservationData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['end']);
    }
}
