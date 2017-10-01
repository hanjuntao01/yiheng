<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsAttr
 */
class GoodsAttr extends Model
{
    protected $table = 'goods_attr';

    protected $primaryKey = 'goods_attr_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'attr_id',
        'attr_value',
        'color_value',
        'attr_price',
        'attr_sort',
        'attr_img_flie',
        'attr_gallery_flie',
        'attr_img_site',
        'attr_checked',
        'attr_value1',
        'lang_flag',
        'attr_img',
        'attr_thumb',
        'img_flag',
        'attr_pid',
        'admin_id'
    ];

    protected $guarded = [];
}
