<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillingTransaction;
use App\Models\Clinic;
use App\Models\MedicalTransactions;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingTransactionTest extends TestCase
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

    public function test_can_list_all_billing_transactions()
    {
        BillingTransaction::factory()->forReservation()->count(3)->create();

        $response = $this->getJson('/api/transactions/billing');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'amount',
                        'description',
                        'user' => ['id', 'firstName', 'lastName'],
                        'reservation'
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_can_create_a_billing_transaction_from_reservation()
    {
        $transactionData = [
            'amount' => 100.50,
            'description' => 'Test transaction',
            'type' => 'in',
            'modelType' => 'reservation',
            'modelId' => Reservation::factory()->create()->id,
        ];

        $response = $this->postJson('/api/transactions/billing', $transactionData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'amount',
                    'description',
                    'reservation'
                ]
            ]);
    }

    public function test_can_create_a_billing_transaction_from_medical_transaction()
    {
        $transactionData = [
            'amount' => 100.50,
            'description' => 'Test transaction',
            'type' => 'in',
            'modelType' => 'medicalTransaction',
            'modelId' => MedicalTransactions::factory()->create()->id,
        ];

        $response = $this->postJson('/api/transactions/billing', $transactionData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'amount',
                    'description',
                    'medicalTransaction'
                ]
            ]);
    }

    public function test_can_show_a_specific_billing_transaction()
    {
        $billing = BillingTransaction::factory()
            ->forReservation()
            ->create([
                'clinic_id' => $this->clinic->id,
                'user_id' => $this->authUser->id
            ]);

        $response = $this->getJson("/api/transactions/billing/{$billing->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'amount',
                    'description',
                    'user',
                    'reservation'
                ]
            ]);
    }

    public function test_can_update_a_billing_transaction()
    {
        $transaction = BillingTransaction::factory()->forReservation()->create([
            'clinic_id' => $this->clinic->id,
            'user_id' => $this->authUser->id
        ]);
        $updateData = [
            'amount' => 200.00,
            'description' => 'Updated transaction'
        ];

        $response = $this->putJson("/api/transactions/billing/{$transaction->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'amount',
                    'description'
                ]
            ]);

        $this->assertDatabaseHas('billing_transactions', $updateData);
    }

    public function test_can_delete_a_billing_transaction()
    {
        $transaction = BillingTransaction::factory()->forMedicalTransaction()->create([
            'clinic_id' => $this->clinic->id,
            'user_id' => $this->authUser->id
        ]);

        $response = $this->deleteJson("/api/transactions/billing/{$transaction->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('billing_transactions', ['id' => $transaction->id]);
    }

    public function test_returns_404_when_billing_transaction_not_found()
    {
        $nonExistentId = '00000000-0000-0000-0000-000000000000';

        $response = $this->getJson("/api/transactions/billing/{$nonExistentId}");
        $response->assertStatus(404);

        $response = $this->putJson("/api/transactions/billing/{$nonExistentId}", []);
        $response->assertStatus(404);

        $response = $this->deleteJson("/api/transactions/billing/{$nonExistentId}");
        $response->assertStatus(404);
    }
}