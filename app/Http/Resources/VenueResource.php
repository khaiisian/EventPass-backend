<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->VenueId,
            'code' => $this->VenueCode,
            'name' => $this->VenueName,
            'description' => $this->Description,
            'address' => $this->Address,
            'image' => $this->VenueImage,
            'capacity' => $this->Capacity,
            'venue_type' => [
                'id' => $this->venueType?->VenueTypeId,
                'code' => $this->venueType?->VenueTypeCode,
                'name' => $this->venueType?->VenueTypeName,
            ],
            'created_by' => $this->CreatedBy,
            'created_at' => $this->CreatedAt?->toDateTimeString(),
            'modified_by' => $this->ModifiedBy,
            'modified_at' => $this->ModifiedAt?->toDateTimeString(),
            'deleted' => (bool) $this->DeleteFlag,
        ];
    }
}