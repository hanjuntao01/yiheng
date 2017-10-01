<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsPercent
 */
class MerchantsPercent extends Model
{
    protected $table = 'merchants_percent';

    protected $primaryKey = 'percent_id';

    public $timestamps = false;

    protected $fillable = [
        'percent_value',
        'sort_order',
        'add_time'
    ];

    protected $guarded = [];
}
