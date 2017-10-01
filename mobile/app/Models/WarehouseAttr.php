<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseAttr
 */
class WarehouseAttr extends Model
{
    protected $table = 'warehouse_attr';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'goods_attr_id',
        'warehouse_id',
        'attr_price',
        'admin_id'
    ];

    protected $guarded = [];
}
