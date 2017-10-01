<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingTpl
 */
class ShippingTpl extends Model
{
    protected $table = 'shipping_tpl';

    protected $primaryKey = 'st_id';

    public $timestamps = false;

    protected $fillable = [
        'shipping_id',
        'ru_id',
        'print_bg',
        'print_model',
        'config_lable',
        'shipping_print',
        'update_time'
    ];

    protected $guarded = [];
}
