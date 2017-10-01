<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsTransportExtend
 */
class GoodsTransportExtend extends Model
{
    protected $table = 'goods_transport_extend';

    public $timestamps = false;

    protected $fillable = [
        'tid',
        'ru_id',
        'admin_id',
        'area_id',
        'top_area_id',
        'sprice'
    ];

    protected $guarded = [];
}
