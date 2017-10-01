<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseAreaGoods
 */
class WarehouseAreaGoods extends Model
{
    protected $table = 'warehouse_area_goods';

    protected $primaryKey = 'a_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'goods_id',
        'region_id',
        'region_sn',
        'region_number',
        'region_price',
        'region_promote_price',
        'region_sort',
        'add_time',
        'last_update',
        'give_integral',
        'rank_integral',
        'pay_integral'
    ];

    protected $guarded = [];
}
