<?php

namespace App\Services;

use App\Models\EventType;
use App\Traits\CodeGenerator;

class EventTypeService
{
    use CodeGenerator;
    public function connection()
    {
        return new EventType;
    }

    public function getAll($perPage = 10)
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->paginate($perPage);
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
        $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['CreatedAt'] = now();
        $data['EventTypeCode'] = $this->generateCode('EVT', 'EventTypeId', 'EventTypeCode', EventType::class);
        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {

        $data['ModifiedAt'] = now();
        $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';

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