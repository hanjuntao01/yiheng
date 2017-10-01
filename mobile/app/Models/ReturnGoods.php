<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReturnGoods
 */
class ReturnGoods extends Model
{
    protected $table = 'return_goods';

    protected $primaryKey = 'rg_id';

    public $timestamps = false;

    protected $fillable = [
        'rec_id',
        'ret_id',
        'goods_id',
        'product_id',
        'product_sn',
        'goods_name',
        'brand_name',
        'goods_sn',
        'is_real',
        'goods_attr',
        'attr_id',
        'return_type',
        'return_number',
        'out_attr',
        'return_attr_id',
        'refound'
    ];

    protected $guarded = [];
}
