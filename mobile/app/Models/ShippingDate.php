<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingDate
 */
class ShippingDate extends Model
{
    protected $table = 'shipping_date';

    protected $primaryKey = 'shipping_date_id';

    public $timestamps = false;

    protected $fillable = [
        'start_date',
        'end_date',
        'select_day',
        'select_date'
    ];

    protected $guarded = [];
}
