<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductsWarehouse
 */
class ProductsWarehouse extends Model
{
    protected $table = 'products_warehouse';

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'goods_attr',
        'product_sn',
        'bar_code',
        'product_number',
        'product_price',
        'product_market_price',
        'product_warn_number',
        'warehouse_id',
        'admin_id'
    ];

    protected $guarded = [];
}
