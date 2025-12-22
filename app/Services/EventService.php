<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function connection()
    {
        return new Event;
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
            ->where('EventId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        $data['EventCode'] = $this->generateEventCode();
        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
        $event = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $event->update($data);
        return $event;
    }

    public function destroy($id)
    {
        $event = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $event->DeleteFlag = true;
        $event->ModifiedAt = now();
        $event->ModifiedBy = "system";

        return $event->save();
    }

    public function generateEventCode()
    {
        $last = $this->connection()::orderBy('EventId', 'desc')->first();

        if (!$last) {
            return 'EV0001';
        }

        $lastCode = $last->EventCode;
        $number = (int) substr($lastCode, 2);
        $number++;
        return 'EV' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}