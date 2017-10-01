<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsTransportExpress
 */
class GoodsTransportExpress extends Model
{
    protected $table = 'goods_transport_express';

    public $timestamps = false;

    protected $fillable = [
        'tid',
        'ru_id',
        'admin_id',
        'shipping_id',
        'shipping_fee'
    ];

    protected $guarded = [];
}
