<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsTransportTpl
 */
class GoodsTransportTpl extends Model
{
    protected $table = 'goods_transport_tpl';

    public $timestamps = false;

    protected $fillable = [
        'tid',
        'user_id',
        'shipping_id',
        'region_id',
        'configure'
    ];

    protected $guarded = [];
}
