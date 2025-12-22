<?php

namespace App\Services;

use App\Models\Event;
use App\Traits\CodeGenerator;


class EventService
{
    use CodeGenerator;
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
        $data['CreatedBy'] = 'admin';
        $data['CreatedAt'] = now();
        $data['EventCode'] = $this->generateCode('EV', 'EventId', 'EventCode', Event::class);

        try {
            return $this->connection()->create($data);
        } catch (\Exception $e) {
            // Log the actual error
            \Log::error('Event creation failed: ' . $e->getMessage());
            throw $e;
        }
    }


    public function update(array $data, $id)
    {
        $data['ModifiedAt'] = now();
        $data['ModifiedBy'] = 'admin';
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

}