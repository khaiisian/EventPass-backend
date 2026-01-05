<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'Tbl_Event';
    protected $primaryKey = 'EventId';
    public $timestamps = false;

    protected $fillable = [
        'EventCode',
        'EventTypeId',
        'VenueId',
        'EventName',
        'StartDate',
        'EndDate',
        'IsActive',
        'EventImage',
        'EventStatus',
        'TotalTicketQuantity',
        'SoldOutTicketQuantity',
        'OrganizerId',
        'CreatedBy',
        'CreatedAt',
        'ModifiedBy',
        'ModifiedAt',
        'DeleteFlag',
    ];

    protected $casts = [
        'EventId' => 'integer',
        'EventTypeId' => 'integer',
        'VenueId' => 'integer',
        'IsActive' => 'boolean',
        'DeleteFlag' => 'boolean',
        'EventStatus' => 'integer',
        'TotalTicketQuantity' => 'integer',
        'SoldOutTicketQuantity' => 'integer',
        'StartDate' => 'datetime',
        'EndDate' => 'datetime',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'EventTypeId', 'EventTypeId');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'VenueId', 'VenueId');
    }
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'OrganizerId', 'OrganizerId');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'EventId', 'EventId');
    }

    public function transactionTickets()
    {
        return $this->hasManyThrough(
            TransactionTicket::class,
            TicketType::class,
            'EventId',
            'TicketTypeId',
            'EventId',
            'TicketTypeId'
        );
    }
}