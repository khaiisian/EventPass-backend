<?php

namespace App\Services;

use App\Models\Venue;
use App\Traits\CodeGenerator;
use Illuminate\Support\Facades\Storage;


class VenueService
{
    use CodeGenerator;
    public function connection()
    {
        return new Venue;
    }

    public function getAll()
    {
        return $this->connection()
            ->with('venueType')
            ->where('DeleteFlag', false)
            ->get();
    }

    public function getById($id)
    {
        return $this->connection()
            ->with('venueType')
            ->where('VenueId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['CreatedAt'] = now();
        $data['VenueCode'] = $this->generateCode('VEN', 'VenueId', 'VenueCode', Venue::class);

        if (!empty($data['VenueImage'])) {
            $image = $data['VenueImage'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['VenueImage'] = $image->storeAs('images', $imageName, 'public');
        }

        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
        $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['ModifiedAt'] = now();
        $venue = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        if (!empty($data['VenueImage'])) {
            if ($venue->VenueImage) {
                Storage::disk('public')->delete($venue->VenueImage);
            }

            $image = $data['VenueImage'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['VenueImage'] = $image->storeAs('images', $imageName, 'public');
        }

        $venue->update($data);
        return $venue;
    }

    public function destroy($id)
    {
        $venue = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $venue->DeleteFlag = true;
        $venue->ModifiedAt = now();
        $venue->ModifiedBy = 'system';

        return $venue->save();
    }

    public function generateVenueCode()
    {
        $last = $this->connection()::orderBy('VenueId', 'desc')->first();

        if (!$last) {
            return 'VEN0001';
        }

        $lastCode = $last->VenueCode;
        $number = (int) substr($lastCode, 3);
        $number++;
        return 'VEN' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}