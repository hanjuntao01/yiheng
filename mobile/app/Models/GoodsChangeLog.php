<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsChangeLog
 */
class GoodsChangeLog extends Model
{
    protected $table = 'goods_change_log';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'shop_price',
        'shipping_fee',
        'promote_price',
        'member_price',
        'volume_price',
        'give_integral',
        'rank_integral',
        'goods_weight',
        'is_on_sale',
        'user_id',
        'handle_time',
        'old_record'
    ];

    protected $guarded = [];
}
