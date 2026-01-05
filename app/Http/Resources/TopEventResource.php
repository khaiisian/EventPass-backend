<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'EventId' => $this->EventId,
            'EventCode' => $this->EventCode,
            'EventTypeId' => $this->EventTypeId,
            'VenueId' => $this->VenueId,
            'EventName' => $this->EventName,
            'OrganizerId' => $this->OrganizerId,

            'StartDate' => $this->StartDate
                ? $this->StartDate->toDateTimeString()
                : null,

            'EndDate' => $this->EndDate
                ? $this->EndDate->toDateTimeString()
                : null,

            'IsActive' => (bool) $this->IsActive,
            'EventStatus' => $this->EventStatus,
            'TotalTicketQuantity' => $this->TotalTicketQuantity,
            'SoldOutTicketQuantity' => $this->SoldOutTicketQuantity,

            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt
                ? $this->CreatedAt->toDateTimeString()
                : null,

            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt
                ? $this->ModifiedAt->toDateTimeString()
                : null,

            'DeleteFlag' => (bool) $this->DeleteFlag,

            'EventType' => $this->whenLoaded('eventType'),
            'Venue' => $this->whenLoaded('venue'),
            'Organizer' => $this->whenLoaded('organizer'),
            'TicketType' => $this->whenLoaded('ticketTypes'),
        ];
    }
}