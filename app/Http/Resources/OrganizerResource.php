<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'OrganizerId' => $this->OrganizerId,
            'OrganizerCode' => $this->OrganizerCode,
            'OrganizerName' => $this->OrganizerName,
            'Email' => $this->Email,
            'PhNumber' => $this->PhNumber,
            'Address' => $this->Address,

            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt
                ? $this->CreatedAt->toDateTimeString()
                : null,

            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt
                ? $this->ModifiedAt->toDateTimeString()
                : null,

            'DeleteFlag' => (bool) $this->DeleteFlag,
        ];
    }
}