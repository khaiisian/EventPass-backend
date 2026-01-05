<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'VenueTypeId' => $this->VenueTypeId,
            'VenueTypeCode' => $this->VenueTypeCode,
            'VenueTypeName' => $this->VenueTypeName,
            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt ? $this->CreatedAt->toDateTimeString() : null,
            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt ? $this->ModifiedAt->toDateTimeString() : null,
            'DeleteFlag' => (bool) $this->DeleteFlag,
        ];
    }
}