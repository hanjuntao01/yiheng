<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersPaypwd
 */
class UsersPaypwd extends Model
{
    protected $table = 'users_paypwd';

    protected $primaryKey = 'paypwd_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ec_salt',
        'pay_password',
        'pay_online',
        'user_surplus',
        'user_point',
        'baitiao',
        'gift_card'
    ];

    protected $guarded = [];
}
