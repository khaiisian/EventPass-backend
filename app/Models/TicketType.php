<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $table = 'Tbl_TicketType';
    protected $primaryKey = 'TicketTypeId';
    public $timestamps = false;

    protected $fillable = [
        'TicketTypeCode',
        'EventId',
        'TicketTypeName',
        'Price',
        'TotalQuantity',
        'SoldQuantity',
        'CreatedBy',
        'ModifiedBy',
        'DeleteFlag',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'TotalQuantity' => 'integer',
        'SoldQuantity' => 'integer',
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];

    // Relationship to Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'EventId', 'EventId');
    }
}