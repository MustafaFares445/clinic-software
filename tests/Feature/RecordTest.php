<?php

namespace Tests\Feature;

use App\Enums\RecordTypes;
use App\Http\Resources\RecordResource;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    protected User $doctor;
    protected Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor = User::factory()->withRole(['doctor' , 'admin'])->create();
        $this->patient = Patient::factory()->create();

        $this->actingAs($this->doctor);
    }

    public function test_create_record()
    {
        $reservation = Reservation::factory()->create();

        $response = $this->postJson('/api/records', [
            'patientId' => $this->patient->id,
            'reservationId' => $reservation->id,
            'type' => RecordTypes::APPOINTMENT->value,
            'description' => 'Test record',
            'doctorsIds' => [$this->doctor->id],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'description',
                    'type',
                    'patient',
                    'doctors',
                    'reservation'
                ]
            ]);
    }

    public function test_get_record()
    {
        $record = Record::factory()->create();

        $response = $this->getJson("/api/records/{$record->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'description',
                    'type',
                    'patient',
                    'doctors',
                    'reservation'
                ]
            ]);
    }

    public function test_update_record()
    {
        $record = Record::factory()->create();

        $response = $this->putJson("/api/records/{$record->id}", [
            'description' => 'Updated description',
            'type' => 'transient',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['description' => 'Updated description']);
    }

    public function test_delete_record()
    {
        $record = Record::factory()->create();

        $response = $this->deleteJson("/api/records/{$record->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted($record);
    }

    public function test_restore_record()
    {
        $record = Record::factory()->create(['deleted_at' => now()]);

        $response = $this->postJson("/api/records/{$record->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('records', ['id' => $record->id, 'deleted_at' => null]);
    }

    public function test_add_media_to_record()
    {
        Storage::fake('public');

        $record = Record::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson("/api/records/{$record->id}/media", [
            'image' => $file,
            'collection' => 'images'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'file_name',
                    'collection_name'
                ]
            ]);
    }

    public function test_delete_media_from_record()
    {
        $record = Record::factory()->create();
        $media = $record->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('images');

        $response = $this->deleteJson("/api/records/{$record->id}/media/{$media->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted($media);
    }

    public function test_cannot_delete_media_from_other_record()
    {
        $record1 = Record::factory()->create();
        $record2 = Record::factory()->create();
        $media = $record1->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('images');

        $response = $this->deleteJson("/api/records/{$record2->id}/media/{$media->id}");

        $response->assertStatus(403);
    }
}