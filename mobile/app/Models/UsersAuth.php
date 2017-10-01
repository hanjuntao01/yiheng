<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersAuth
 */
class UsersAuth extends Model
{
    protected $table = 'users_auth';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'identity_type',
        'identifier',
        'credential',
        'verified',
        'add_time',
        'update_time'
    ];

    protected $guarded = [];
}
