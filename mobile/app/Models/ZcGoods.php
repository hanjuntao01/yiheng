<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcGoods
 */
class ZcGoods extends Model
{
    protected $table = 'zc_goods';

    public $timestamps = false;

    protected $fillable = [
        'pid',
        'limit',
        'backer_num',
        'price',
        'shipping_fee',
        'content',
        'img',
        'return_time',
        'backer_list'
    ];

    protected $guarded = [];
}
