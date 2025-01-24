<?php

namespace App\Services;

use App\Enums\RecordMedicinesTypes;
use App\Models\Record;

class RecordService
{
    /**
     * Attach multiple medicines to a record with pivot data.
     *
     * @param Record $record
     * @param array $values Array of medicine data (each item should contain 'id' and 'note')
     * @param string $type Type of medicine (default: DIAGNOSED)
     */
    public function insertMedicines(Record $record, array $values, string $type = RecordMedicinesTypes::DIAGNOSED): void
    {
        $pivotData = $this->preparePivotData($values, $type);

        $record->medicines()->attach($pivotData);
    }

    /**
     * Attach multiple ills to a record with pivot data.
     *
     * @param Record $record
     * @param array $values Array of ill data (each item should contain 'id' and 'note')
     * @param string $type Type of ill (default: DIAGNOSED)
     */
    public function insertIlls(Record $record, array $values, string $type = RecordMedicinesTypes::DIAGNOSED): void
    {
        $pivotData = $this->preparePivotData($values, $type);

        $record->ills()->attach($pivotData);
    }

    /**
     * Prepare pivot data for attaching relationships.
     *
     * @param array $values Array of data (each item should contain 'id' and 'note')
     * @param string $type Type of relationship (e.g., DIAGNOSED)
     * @return array
     */
    protected function preparePivotData(array $values, string $type): array
    {
        $ids = array_column($values, 'id');
        $pivotData = array_map(function ($item) use ($type) {
            return [
                'type' => $type,
                'note' => $item['note'] ?? null,
            ];
        }, $values);

        return array_combine($ids, $pivotData);
    }
}
