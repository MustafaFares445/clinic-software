<?php

namespace App\Actions;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Handles the creation or update of a Record model and its relationships
 */
class CreateOrUpdateRecordAction
{
    /**
     * Executes the record creation or update process
     *
     * @param Request $request The HTTP request containing record data
     * @param Record|null $record The record to update (null for creation)
     * @return Record The created or updated record
     */
    public function handle(Request $request, ?Record $record = null)
    {
        return DB::transaction(function () use ($request, $record) {
            // Create or update the base record
            $record = $record ? $this->updateRecord($request, $record) : $this->createRecord($request);

            // Handle relationships
            $this->handleDoctors($request, $record);
            $this->handleMedicines($request, $record);
            $this->handleIlls($request, $record);

            return $record;
        });
    }

    /**
     * Creates a new record from the request data
     *
     * @param Request $request The HTTP request containing record data
     * @return Record The newly created record
     */
    protected function createRecord(Request $request): Record
    {
        return Record::create($request->validated());
    }

    /**
     * Updates an existing record with the request data
     *
     * @param Request $request The HTTP request containing record data
     * @param Record $record The record to update
     * @return Record The updated record
     */
    protected function updateRecord(Request $request, Record $record): Record
    {
        $record->update($request->validated());
        return $record;
    }

    /**
     * Handles the synchronization of doctors for the record
     *
     * @param Request $request The HTTP request containing doctors data
     * @param Record $record The record to sync doctors with
     */
    protected function handleDoctors(Request $request, Record $record): void
    {
        if ($request->has('doctorsIds')) {
            $record->doctors()->sync($request->doctorsIds);
        }
    }

    /**
     * Handles the synchronization of medicines for the record
     *
     * @param Request $request The HTTP request containing medicines data
     * @param Record $record The record to sync medicines with
     */
    protected function handleMedicines(Request $request, Record $record): void
    {
        if ($request->has('medicines')) {
            $medicinesData = collect($request->medicines)->map(function ($medicine) {
                return [
                    'medicine_id' => $medicine['id'],
                    'notes' => $medicine['notes'] ?? null,
                    'type' => $medicine['type']
                ];
            });
            $record->medicines()->sync($medicinesData);
        }
    }

    /**
     * Handles the synchronization of ills for the record
     *
     * @param Request $request The HTTP request containing ills data
     * @param Record $record The record to sync ills with
     */
    protected function handleIlls(Request $request, Record $record): void
    {
        if ($request->has('ills')) {
            $illsData = collect($request->ills)->map(function ($ill) {
                return [
                    'ill_id' => $ill['id'],
                    'notes' => $ill['notes'] ?? null,
                    'type' => $ill['type']
                ];
            });
            $record->ills()->sync($illsData);
        }
    }
}