<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkGoodsDesc
 */
class LinkGoodsDesc extends Model
{
    protected $table = 'link_goods_desc';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'desc_name',
        'goods_desc'
    ];

    protected $guarded = [];
}
