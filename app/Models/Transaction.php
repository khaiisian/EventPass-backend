<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'Tbl_Transaction';
    protected $primaryKey = 'TransactionId';
    public $timestamps = false;

    protected $fillable = [
        'TransactionCode',
        'UserId',
        'Email',
        'Status',
        'PaymentType',
        'TotalAmount',
        'TransactionDate',
        'CreatedBy',
        'CreatedAt',
        'ModifiedBy',
        'ModifiedAt',
        'DeleteFlag',
    ];

    protected $casts = [
        'Status' => 'boolean',
        'TotalAmount' => 'decimal:2',
        'TransactionDate' => 'datetime',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
        'DeleteFlag' => 'boolean',
    ];

    public function transactionTickets()
    {
        return $this->hasMany(
            TransactionTicket::class,
            'TransactionId',
            'TransactionId'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'UserId');
    }
}