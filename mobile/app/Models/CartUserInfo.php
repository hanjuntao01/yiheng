<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CartUserInfo
 */
class CartUserInfo extends Model
{
    protected $table = 'cart_user_info';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'user_id',
        'shipping_type',
        'shipping_id'
    ];

    protected $guarded = [];
}
