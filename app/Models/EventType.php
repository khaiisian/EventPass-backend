<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $table = 'Tbl_EventType';
    protected $primaryKey = 'EventTypeId';
    public $timestamps = false;

    protected $fillable = [
        'EventTypeCode',
        'EventTypeName',
        'CreatedBy',
        'ModifiedBy',
        'DeleteFlag',
    ];

    protected $casts = [
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];
}