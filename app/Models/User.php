<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    protected $table = 'Tbl_User';
    protected $primaryKey = 'UserId';
    public $timestamps = false;

    protected $fillable = [
        'UserCode',
        'UserName',
        'Email',
        'PhNumber',
        'Password',
        'ProfileImg',
        'Role',          // <-- Use the exact column name from migration
        'CreatedBy',
        'CreatedAt',
        'ModifiedBy',
        'ModifiedAt',
        'DeleteFlag',
    ];

    protected $hidden = ['Password'];

    protected $casts = [
        'DeleteFlag' => 'boolean',
        'CreatedAt' => 'datetime',
        'ModifiedAt' => 'datetime',
        'Role' => 'string',  // <-- match the column name
    ];

    // Map JWTAuth attempt 'password' field to your 'Password' column
    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}