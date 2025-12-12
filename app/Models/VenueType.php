<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueType extends Model
{
    // Table name
    protected $table = 'Tbl_VenueType';

    // Primary key
    protected $primaryKey = 'VenueTypeId';

    // Laravel won't automatically manage timestamps
    public $timestamps = false;

    // Mass assignable fields
    protected $fillable = [
        'VenueTypeCode',
        'VenueTypeName',
        'CreatedBy',
        'ModifiedBy',
        'DeleteFlag',
    ];

    // Cast types
    protected $casts = [
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];
}