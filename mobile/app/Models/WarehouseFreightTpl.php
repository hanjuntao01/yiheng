<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseFreightTpl
 */
class WarehouseFreightTpl extends Model
{
    protected $table = 'warehouse_freight_tpl';

    public $timestamps = false;

    protected $fillable = [
        'tpl_name',
        'user_id',
        'warehouse_id',
        'shipping_id',
        'region_id',
        'configure'
    ];

    protected $guarded = [];
}
