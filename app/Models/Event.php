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
}