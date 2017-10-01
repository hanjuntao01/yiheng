<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsRegionInfo
 */
class MerchantsRegionInfo extends Model
{
    protected $table = 'merchants_region_info';

    public $timestamps = false;

    protected $fillable = [
        'ra_id',
        'region_id'
    ];

    protected $guarded = [];
}
