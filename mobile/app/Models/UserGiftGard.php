<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserGiftGard
 */
class UserGiftGard extends Model
{
    protected $table = 'user_gift_gard';

    protected $primaryKey = 'gift_gard_id';

    public $timestamps = false;

    protected $fillable = [
        'gift_sn',
        'gift_password',
        'user_id',
        'goods_id',
        'user_time',
        'express_no',
        'gift_id',
        'address',
        'consignee_name',
        'mobile',
        'status',
        'config_goods_id',
        'is_delete',
        'shipping_time'
    ];

    protected $guarded = [];
}
