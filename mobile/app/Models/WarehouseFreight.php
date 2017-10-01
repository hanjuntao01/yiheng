<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseFreight
 */
class WarehouseFreight extends Model
{
    protected $table = 'warehouse_freight';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'shipping_id',
        'region_id',
        'configure'
    ];

    protected $guarded = [];
}
