<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreProducts
 */
class StoreProducts extends Model
{
    protected $table = 'store_products';

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'goods_attr',
        'product_sn',
        'product_number',
        'ru_id',
        'store_id'
    ];

    protected $guarded = [];
}
