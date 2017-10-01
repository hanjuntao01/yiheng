<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DrpShop
 */
class DrpShop extends Model
{
    protected $table = 'drp_shop';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'shop_name',
        'real_name',
        'mobile',
        'qq',
        'shop_img',
        'cat_id',
        'create_time',
        'isbuy',
        'audit',
        'status',
        'shop_money',
        'shop_points',
        'type'
    ];

    protected $guarded = [];
}
