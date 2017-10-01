<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreUser
 */
class StoreUser extends Model
{
    protected $table = 'store_user';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'store_id',
        'parent_id',
        'stores_user',
        'stores_pwd',
        'tel',
        'email',
        'store_action',
        'add_time',
        'ec_salt',
        'store_user_img'
    ];

    protected $guarded = [];
}
