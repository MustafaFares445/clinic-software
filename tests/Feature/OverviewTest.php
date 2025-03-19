<?php

namespace Tests\Feature;

use App\Enums\RecordTypes;
use App\Models\Clinic;
use App\Models\Ill;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OverviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $authUser;
    protected Clinic $clinic;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->clinic = Clinic::factory()->create();
        $this->authUser = User::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $this->actingAs($this->authUser);
    }

    public function test_can_gets_patient_count_by_gender()
    {
        Patient::factory()->count(3)->create(['gender' => 'male']);
        Patient::factory()->count(5)->create(['gender' => 'female']);

        $response = $this->getJson('/api/overview/patients/gender/count');

        $response->assertStatus(200)
            ->assertJson([
                'malesCount' => 3,
                'femalesCount' => 5
            ]);
    }

    public function test_can_gets_illness_statistics()
    {
        $ill = Ill::factory()->create();

        // Create records with related ill and specific clinic
        Record::factory()
            ->count(3)
            ->for($this->authUser->clinic)
            ->hasAttached($ill)
            ->create();

        $response = $this->getJson('/api/overview/ills/count');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'records_count']
                ],
                'totalCount'
            ]);
    }

    public function test_can_gets_records_count_within_date_range()
    {
        Record::factory()->create(['dateTime' => Carbon::now()->subDays(2)]);
        Record::factory()->create(['dateTime' => Carbon::now()->subDays(1)]);

        $response = $this->getJson('/api/overview/records/count', [
            'startDate' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'endDate' => Carbon::now()->format('Y-m-d')
        ]);

        $response->assertStatus(200)
            ->assertJson(['count' => 2]);
    }

    public function test_can_gets_general_statistics()
    {
        Reservation::factory()->count(2)->create();
        Record::factory()->create(['type' => RecordTypes::SURGERY]);
        Record::factory()->create(['type' => RecordTypes::APPOINTMENT]);
        Record::factory()->create(['type' => RecordTypes::INSPECTION]);

        $response = $this->getJson('/api/overview/general-statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'reservationsCount',
                'surgeryCount',
                'appointmentCount',
                'inspectionCount'
            ]);
    }

    public function test_can_gets_top_5_illnesses()
    {
        $ill1 = Ill::factory()->create();
        $ill2 = Ill::factory()->create();

        // Create records with related ills and specific clinic
        Record::factory()
            ->count(3)
            ->for($this->authUser->clinic)
            ->hasAttached($ill1)
            ->create();

        Record::factory()
            ->count(2)
            ->for($this->authUser->clinic)
            ->hasAttached($ill2)
            ->create();

        $response = $this->getJson('/api/overview/top-ills');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_gets_patients_count()
    {
        Patient::factory()->count(3)->create(['created_at' => Carbon::now()]);
        Patient::factory()->count(2)->create(['created_at' => Carbon::now()->subMonth()]);

        $response = $this->getJson('/api/overview/patients/count');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'count',
                'currentMonth'
            ]);
    }
}