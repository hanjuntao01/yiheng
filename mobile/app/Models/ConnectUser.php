<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConnectUser
 */
class ConnectUser extends Model
{
    protected $table = 'connect_user';

    public $timestamps = false;

    protected $fillable = [
        'connect_code',
        'user_id',
        'is_admin',
        'open_id',
        'refresh_token',
        'access_token',
        'profile',
        'create_at',
        'expires_in',
        'expires_at'
    ];

    protected $guarded = [];
}
