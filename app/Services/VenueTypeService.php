<?php

namespace App\Services;

use App\Models\VenueType;

class VenueTypeService
{
    // Get a fresh model instance
    public function connection()
    {
        return new VenueType;
    }

    // Get all active venue types
    public function getAll()
    {
        return $this->connection()
            ->query()
            ->where('DeleteFlag', false)
            ->get();
    }

    // Get a single venue type by ID
    public function getById($id)
    {
        return $this->connection()
            ->where('VenueTypeId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    // Create a new venue type
    public function create(array $data)
    {
        $data['VenueTypeCode'] = $this->generateVenueTypeCode();
        return $this->connection()->query()->create($data);
    }

    // Update an existing venue type
    public function update(array $data, $id)
    {
        $venueType = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $venueType->update($data);
        return $venueType;
    }

    // Soft delete a venue type
    public function destroy($id)
    {
        $venueType = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $venueType->DeleteFlag = true;
        $venueType->ModifiedAt = now();
        $venueType->ModifiedBy = "system";

        return $venueType->save();
    }

    // Generate a unique VenueTypeCode (e.g., VEN0001)
    public function generateVenueTypeCode()
    {
        $last = $this->connection()::orderBy('VenueTypeId', 'desc')->first();

        if (!$last) {
            return 'VEN0001';
        }

        $lastCode = $last->VenueTypeCode;           // e.g., VEN0012
        $number = (int) substr($lastCode, 3);      // 12
        $number++;
        return 'VEN' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}