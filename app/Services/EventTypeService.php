<?php

namespace App\Services;

use App\Models\EventType;

class EventTypeService
{
    public function connection()
    {
        return new EventType;
    }

    public function getAll()
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->get();
    }

    public function getById($id)
    {
        return $this->connection()
            ->where('EventTypeId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        $data['EventTypeCode'] = $this->generateEventTypeCode();
        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
        $eventType = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $eventType->update($data);
        return $eventType;
    }

    public function destroy($id)
    {
        $eventType = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $eventType->DeleteFlag = true;
        $eventType->ModifiedAt = now();
        $eventType->ModifiedBy = "system";

        return $eventType->save();
    }

    public function generateEventTypeCode()
    {
        $last = $this->connection()::orderBy('EventTypeId', 'desc')->first();

        if (!$last) {
            return 'EVT0001';
        }

        $lastCode = $last->EventTypeCode;
        $number = (int) substr($lastCode, 3);
        $number++;
        return 'EVT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}