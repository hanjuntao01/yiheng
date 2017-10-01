<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingArea
 */
class ShippingArea extends Model
{
    protected $table = 'shipping_area';

    protected $primaryKey = 'shipping_area_id';

    public $timestamps = false;

    protected $fillable = [
        'shipping_area_name',
        'shipping_id',
        'configure',
        'ru_id'
    ];

    protected $guarded = [];

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'shipping_id', "shipping_id");
    }
}
