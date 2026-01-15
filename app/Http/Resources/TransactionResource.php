<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'TransactionId' => $this->TransactionId,
            'TransactionCode' => $this->TransactionCode,
            'UserId' => $this->UserId,
            'Email' => $this->Email,
            'Status' => (bool) $this->Status,
            'PaymentType' => $this->PaymentType,
            'TotalAmount' => $this->TotalAmount,
            'TransactionDate' => $this->TransactionDate?->toDateTimeString(),

            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt?->toDateTimeString(),
            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt?->toDateTimeString(),
            'DeleteFlag' => (bool) $this->DeleteFlag,

            'User' => $this->whenLoaded('user'),

            'Event' => $this->whenLoaded('transactionTickets', function () {
                return $this->transactionTickets
                    ->map(fn($ticket) => $ticket->ticketType?->event)
                    ->filter()
                    ->unique('EventId')
                    ->values()
                    ->map(function ($event) {
                        return [
                            'EventId' => $event->EventId,
                            'EventCode' => $event->EventCode,
                            'EventName' => $event->EventName,
                            'StartDate' => $event->StartDate?->toDateTimeString(),
                            'EndDate' => $event->EndDate?->toDateTimeString(),
                            'EventStatus' => $event->EventStatus,
                            'EventImage' => $event->EventImage
                                ? asset('storage/' . $event->EventImage)
                                : null,
                        ];
                    });
            }),

            'TransactionTickets' => $this->whenLoaded('transactionTickets', function () {
                return $this->transactionTickets->map(function ($ticket) {
                    return [
                        'TransactionTicketId' => $ticket->TransactionTicketId,
                        'TransactionTicketCode' => $ticket->TransactionTicketCode,
                        'Price' => $ticket->Price,
                        'QrImage' => $ticket->QrImage
                            ? asset('storage/' . $ticket->QrImage)
                            : null,
                        'TicketStatus' => (bool) $ticket->Status,
                        'TicketType' => $ticket->ticketType ? [
                            'TicketTypeId' => $ticket->ticketType->TicketTypeId,
                            'TicketTypeName' => $ticket->ticketType->TicketTypeName,
                            'Description' => $ticket->ticketType->Description,
                        ] : null,
                    ];
                });
            }),
        ];
    }
}