<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkAreaGoods
 */
class LinkAreaGoods extends Model
{
    protected $table = 'link_area_goods';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'region_id',
        'ru_id'
    ];

    protected $guarded = [];
}
