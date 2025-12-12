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
            'id' => $this->VenueTypeId,
            'code' => $this->VenueTypeCode,
            'name' => $this->VenueTypeName,
            'created_by' => $this->CreatedBy,
            'created_at' => $this->CreatedAt ? $this->CreatedAt->toDateTimeString() : null,
            'modified_by' => $this->ModifiedBy,
            'modified_at' => $this->ModifiedAt ? $this->ModifiedAt->toDateTimeString() : null,
            'deleted' => (bool) $this->DeleteFlag,
        ];
    }
}