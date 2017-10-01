<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkDescGoodsid
 */
class LinkDescGoodsid extends Model
{
    protected $table = 'link_desc_goodsid';

    public $timestamps = false;

    protected $fillable = [
        'd_id',
        'goods_id'
    ];

    protected $guarded = [];
}
