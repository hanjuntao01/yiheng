<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SeckillGoods
 */
class SeckillGoods extends Model
{
    protected $table = 'seckill_goods';

    public $timestamps = false;

    protected $fillable = [
        'sec_id',
        'tb_id',
        'goods_id',
        'sec_price',
        'sec_num',
        'sec_limit'
    ];

    protected $guarded = [];
}
