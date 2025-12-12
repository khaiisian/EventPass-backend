<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Custom table name
    protected $table = 'Tbl_User';

    // Custom primary key
    protected $primaryKey = 'UserId';

    // PK is auto-increment integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Disable default Laravel timestamps (created_at, updated_at)
    public $timestamps = false;

    // Mass assignable fields
    protected $fillable = [
        'UserCode',
        'UserName',
        'Email',
        'PhNumber',
        'Password',
        'ProfileImg',
        'CreatedBy',
        'CreatedAt',
        'ModifiedBy',
        'ModifiedAt',
        'DeleteFlag',
    ];
}