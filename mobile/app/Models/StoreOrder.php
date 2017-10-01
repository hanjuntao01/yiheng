<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreOrder
 */
class StoreOrder extends Model
{
    protected $table = 'store_order';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'store_id',
        'ru_id',
        'is_grab_order',
        'grab_store_list',
        'pick_code',
        'take_time'
    ];

    protected $guarded = [];
}
