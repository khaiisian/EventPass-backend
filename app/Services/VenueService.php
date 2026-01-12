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

    public function getAll($perPage = 10)
    {
        return $this->connection()
            ->with('venueType')
            ->where('DeleteFlag', false)
            ->paginate($perPage);
    }

    public function search(array $params)
    {
        $query = Venue::query()
            ->where('DeleteFlag', false)
            ->with('venueType');

        if (!empty($params['venue_type_id'])) {
            $query->where('VenueTypeId', $params['venue_type_id']);
        }

        if (!empty($params['search'])) {
            $query->where('VenueName', 'LIKE', '%' . $params['search'] . '%');
        }

        switch ($params['sort_by'] ?? null) {
            case 'name_asc':
                $query->orderBy('VenueName', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('VenueName', 'desc');
                break;

            case 'capacity_asc':
                $query->orderBy('Capacity', 'asc');
                break;

            case 'capacity_desc':
                $query->orderBy('Capacity', 'desc');
                break;

            default:
                $query->orderBy('CreatedAt', 'desc');
        }

        return $query->paginate($params['per_page'] ?? 10);
    }

    public function getById($id)
    {
        return $this->connection()
            ->with('venueType')
            ->where('VenueId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function getTopVenues()
    {
        $topVenues = Venue::withCount([
            'events' => function ($q) {
                $q->where('DeleteFlag', false);
            }
        ])
            ->where('DeleteFlag', false)
            ->orderByDesc('events_count')
            ->limit(3)
            ->get();

        return $topVenues;
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