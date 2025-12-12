<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'Tbl_Venue';
    protected $primaryKey = 'VenueId';
    public $timestamps = false;

    protected $fillable = [
        'VenueCode',
        'VenueTypeId',
        'VenueName',
        'Description',
        'Address',
        'VenueImage',
        'Capacity',
        'CreatedBy',
        'ModifiedBy',
        'DeleteFlag',
    ];

    protected $casts = [
        'VenueId' => 'integer',
        'VenueTypeId' => 'integer',
        'Capacity' => 'integer',
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
    ];

    public function venueType()
    {
        return $this->belongsTo(VenueType::class, 'VenueTypeId', 'VenueTypeId');
    }
}