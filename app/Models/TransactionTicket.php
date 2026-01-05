<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionTicket extends Model
{
    protected $table = 'Tbl_TransactionTicket';
    protected $primaryKey = 'TransactionTicketId';
    public $timestamps = false;

    protected $fillable = [
        'TransactionTicketCode',
        'TicketTypeId',
        'TransactionId',
        'QrImage',
        'Price',
        'CreatedBy',
        'CreatedAt',
        'ModifiedBy',
        'ModifiedAt',
        'DeleteFlag',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];


    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'TicketTypeId', 'TicketTypeId');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'TransactionId', 'TransactionId');
    }
}