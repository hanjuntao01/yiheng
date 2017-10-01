<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingPoint
 */
class ShippingPoint extends Model
{
    protected $table = 'shipping_point';

    public $timestamps = false;

    protected $fillable = [
        'shipping_area_id',
        'name',
        'user_name',
        'mobile',
        'address',
        'img_url',
        'anchor',
        'line'
    ];

    protected $guarded = [];
}
