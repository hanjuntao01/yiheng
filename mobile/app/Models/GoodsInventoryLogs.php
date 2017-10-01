<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsInventoryLogs
 */
class GoodsInventoryLogs extends Model
{
    protected $table = 'goods_inventory_logs';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'order_id',
        'use_storage',
        'admin_id',
        'number',
        'model_inventory',
        'model_attr',
        'product_id',
        'warehouse_id',
        'area_id',
        'add_time'
    ];

    protected $guarded = [];
}
