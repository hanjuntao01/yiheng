<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreGoods
 */
class StoreGoods extends Model
{
    protected $table = 'store_goods';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'store_id',
        'ru_id',
        'goods_number',
        'extend_goods_number'
    ];

    protected $guarded = [];
}
