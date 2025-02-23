<?php

namespace App\Services;

use App\Enums\RecordIllsTypes;
use App\Enums\RecordMedicinesTypes;
use App\Http\Requests\RecordRequest;
use App\Models\Record;
use Illuminate\Http\Request;

final class RecordService
{
    /**
     * Handle the relationship updates for a record based on the request data.
     *
     * This method processes the request and updates the record's relationships
     * for medicines, transient medicines, ills, transient ills, and doctors.
     *
     * @param Record        $record  the record model to update
     * @param RecordRequest $request the HTTP request containing the relationship data
     */
    public function recordRelations(Record $record, RecordRequest $request): void
    {
        // Mapping of request keys to their respective methods and types
        $relationMappings = [
            'medicines' => [
                'method' => 'insertMedicines',
                'type' => RecordMedicinesTypes::DIAGNOSED,
            ],
            'transientMedicines' => [
                'method' => 'insertMedicines',
                'type' => RecordMedicinesTypes::TRANSIENT,
            ],
            'ills' => [
                'method' => 'insertIlls',
                'type' => RecordIllsTypes::DIAGNOSED,
            ],
            'transientIlls' => [
                'method' => 'insertIlls',
                'type' => RecordIllsTypes::TRANSIENT,
            ],
        ];

        // Process each relationship mapping
        foreach ($relationMappings as $requestKey => $config) {
            if (! $request->has($requestKey)) {
                continue;
            }

            $this->{$config['method']}(
                $record,
                $request->validated($requestKey),
                $config['type']
            );
        }

        // Sync doctors if the request contains doctorsIds
        if ($request->has('doctorsIds')) {
            $record->doctors()->sync($request->validated('doctorsIds'));
        }
    }

    /**
     * Insert or update medicines for a record.
     *
     * @param Record $record the record model to update
     * @param array  $values array of medicine data, each containing 'id' and optionally 'note'
     * @param string $type   the type of medicine (default: DIAGNOSED)
     */
    public function insertMedicines(Record $record, array $values, string $type = RecordMedicinesTypes::DIAGNOSED): void
    {
        $this->handleRelation($record, 'medicines', $values, $type);
    }

    /**
     * Insert or update ills for a record.
     *
     * @param Record $record the record model to update
     * @param array  $values array of ill data, each containing 'id' and optionally 'note'
     * @param string $type   the type of ill (default: DIAGNOSED)
     */
    public function insertIlls(Record $record, array $values, string $type = RecordIllsTypes::DIAGNOSED): void
    {
        $this->handleRelation($record, 'ills', $values, $type);
    }

    /**
     * Handle the insertion or update of a relationship for a record.
     *
     * This method prepares pivot data, checks for existing relationships,
     * and performs batch attach or update operations.
     *
     * @param Record $record   the record model to update
     * @param string $relation The name of the relationship (e.g., 'medicines', 'ills').
     * @param array  $values   array of relationship data, each containing 'id' and optionally 'note'
     * @param string $type     The type of relationship (e.g., DIAGNOSED, TRANSIENT).
     */
    protected function handleRelation(Record $record, string $relation, array $values, string $type): void
    {
        $pivotData = $this->preparePivotData($values, $type);
        $relationship = $record->{$relation}();
        $foreignKey = $relationship->getRelatedPivotKeyName();

        // Get existing IDs for the relationship
        $existingIds = $relationship
            ->wherePivot('type', $type)
            ->whereIn($foreignKey, array_keys($pivotData))
            ->pluck($foreignKey)
            ->toArray();

        // Split into updates and new attachments
        $toUpdate = array_intersect_key($pivotData, array_flip($existingIds));
        $toAttach = array_diff_key($pivotData, $toUpdate);

        // Batch attach new relationships
        if (! empty($toAttach)) {
            $relationship->attach($toAttach);
        }

        // Update existing relationships
        foreach ($toUpdate as $id => $attributes) {
            $relationship->updateExistingPivot($id, $attributes);
        }
    }

    /**
     * Prepare pivot data for attaching relationships.
     *
     * This method transforms the input array into a format suitable for attaching
     * or updating pivot tables, including the relationship type and optional notes.
     *
     * @param array  $values array of data, each containing 'id' and optionally 'note'
     * @param string $type   The type of relationship (e.g., DIAGNOSED, TRANSIENT).
     *
     * @return array an associative array where keys are IDs and values are pivot attributes
     */
    protected function preparePivotData(array $values, string $type): array
    {
        return array_reduce($values, function ($carry, $item) use ($type) {
            $carry[$item['id']] = [
                'type' => $type,
                'note' => $item['note'] ?? null,
            ];

            return $carry;
        }, []);
    }
}
