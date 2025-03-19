<?php

namespace Tests\Feature;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

final class ReservationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Clinic $clinic;
    protected Patient $patient;
    protected  Specification $specification;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clinic = Clinic::factory()->create([
            'start' => '08:00:00',
            'end' => '17:00:00'
        ]);

        $this->user = User::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $this->specification = Specification::factory()->create();

        $this->patient = Patient::factory()->create();
    }

    public function test_can_get_reservations()
    {
        Reservation::factory()->create([
            'clinic_id' => $this->clinic->id,
            'start' => now()->setTime(10, 0),
            'end' => now()->setTime(11, 0)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'start',
                        'end',
                        'type',
                        'status',
                        'patient' => [
                            'id',
                            'firstName',
                            'lastName',
                            'avatar',
                            'media'
                        ],
                        'doctor' => [
                            'id',
                            'firstName',
                            'lastName',
                            'avatar'
                        ],
                        'specification' => [
                            'id',
                            'name',
                            'image'
                        ],
                        'createdAt'
                    ]
                ]
            ])
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_a_reservation()
    {
        $data = [
            'clinicId' => $this->clinic->id,
            'patientId' => $this->patient->id,
            'doctorId' => $this->user->id,
            'specificationId' => $this->specification->id,
            'start' => now()->addMinutes(5)->toDateTimeString(),
            'end' => now()->addMinutes(20)->toDateTimeString(),
            'status' => ReservationStatuses::INCOME->value,
            'type' => ReservationTypes::APPOINTMENT->value,
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/reservations', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'start',
                    'end',
                    'status',
                    'patient',
                    'doctor',
                    'specification'
                ]
            ]);
    }

    public function test_can_show_a_reservation()
    {
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'start',
                    'end',
                    'status',
                    'patient',
                    'doctor',
                    'specification'
                ]
            ]);
    }

    public function test_can_update_a_reservation()
    {
        $reservation = Reservation::factory()->create();
        $newData = [
            'start' => now()->addMinutes(5)->toDateTimeString(),
            'end' => now()->addMinutes(20)->toDateTimeString(),
            'status' => ReservationStatuses::CHECK->value
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/reservations/{$reservation->id}", $newData);

        $response->assertStatus(200)
            ->assertJsonFragment($newData);
    }

    public function test_can_delete_a_reservation()
    {
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_can_updates_income_reservations_to_check_when_they_end()
    {
        $oldReservation = Reservation::factory()->create([
            'start' => now()->subDays(2),
            'end' => now()->subDay(),
            'status' => ReservationStatuses::INCOME
        ]);

        $this->actingAs($this->user)
            ->getJson('/api/reservations');

        $this->assertEquals(ReservationStatuses::CHECK->value, $oldReservation->fresh()->status);
    }

    public function test_can_filters_reservations_by_date_range()
    {
        $inRange = Reservation::factory()->create([
            'start' => now()->setTime(10, 0),
            'end' => now()->setTime(11, 0)
        ]);
        $outOfRange = Reservation::factory()->create([
            'start' => now()->addWeek(),
            'end' => now()->addWeek()->addHour()
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/reservations?start=' . now()->startOfWeek(Carbon::SATURDAY) . '&end=' . now()->endOfWeek(Carbon::FRIDAY));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $inRange->id])
            ->assertJsonMissing(['id' => $outOfRange->id]);
    }
}