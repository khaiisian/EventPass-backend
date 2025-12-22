<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'TicketTypeId' => $this->TicketTypeId,
            'TicketTypeCode' => $this->TicketTypeCode,
            'EventId' => $this->EventId,
            'TicketTypeName' => $this->TicketTypeName,
            'Price' => $this->Price,
            'TotalQuantity' => $this->TotalQuantity,
            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt ? $this->CreatedAt->toDateTimeString() : null,
            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt ? $this->ModifiedAt->toDateTimeString() : null,
            'Deleted' => (bool) $this->DeleteFlag,
        ];
    }
}