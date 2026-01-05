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
            'VenueId' => $this->VenueId,
            'VenueCode' => $this->VenueCode,
            'VenueName' => $this->VenueName,
            'Description' => $this->Description,
            'Address' => $this->Address,
            'VenueImage' => $this->VenueImage ? asset('storage/' . $this->VenueImage) : null,
            'Capacity' => $this->Capacity,
            'venueType' => [
                'VenueTypeId' => $this->venueType?->VenueTypeId,
                'VenueTypeCode' => $this->venueType?->VenueTypeCode,
                'VenueTypeName' => $this->venueType?->VenueTypeName,
            ],
            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt?->toDateTimeString(),
            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt?->toDateTimeString(),
            'DeleteFlag' => (bool) $this->DeleteFlag,
        ];
    }
}