<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseAreaAttr
 */
class WarehouseAreaAttr extends Model
{
    protected $table = 'warehouse_area_attr';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'goods_attr_id',
        'area_id',
        'attr_price',
        'admin_id'
    ];

    protected $guarded = [];
}
