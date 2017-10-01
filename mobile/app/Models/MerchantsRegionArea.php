<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsRegionArea
 */
class MerchantsRegionArea extends Model
{
    protected $table = 'merchants_region_area';

    protected $primaryKey = 'ra_id';

    public $timestamps = false;

    protected $fillable = [
        'ra_name',
        'ra_sort',
        'add_time',
        'up_titme'
    ];

    protected $guarded = [];
}
