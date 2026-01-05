<?php

namespace App\Services;

use App\Models\Organizer;
use App\Traits\CodeGenerator;
use Exception;

class OrganizerService
{
    use CodeGenerator;

    public function connection()
    {
        return new Organizer;
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
            ->where('OrganizerId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['CreatedAt'] = now();
        $data['OrganizerCode'] = $this->generateCode('ORG', 'OrganizerId', 'OrganizerCode', Organizer::class);

        try {
            return $this->connection()->create($data);
        } catch (Exception $e) {
            \Log::error('Organizer creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        $data['ModifiedAt'] = now();
        $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';

        $organizer = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $organizer->update($data);
        return $organizer;
    }

    public function destroy($id)
    {
        $organizer = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $organizer->DeleteFlag = true;
        $organizer->ModifiedAt = now();
        $organizer->ModifiedBy = 'system';

        return $organizer->save();
    }
}