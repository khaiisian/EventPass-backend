<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class organizer extends Model
{
    protected $table = 'Tbl_EventOrganizer';
    protected $primaryKey = 'OrganizerId';
    public $incrementing = true;
    protected $keyType = 'int';

    // Map custom timestamps
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'ModifiedAt';
    public $timestamps = true;

    protected $fillable = [
        'OrganizerCode',
        'OrganizerName',
        'Email',
        'PhNumber',
        'Address',
        'CreatedBy',
        'CreatedAt',
        'ModifiedBy',
        'ModifiedAt',
        'DeleteFlag',
    ];

    protected $casts = [
        'OrganizerId' => 'integer',
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];
}