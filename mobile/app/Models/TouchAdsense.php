<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchAdsense
 */
class TouchAdsense extends Model
{
    protected $table = 'touch_adsense';

    public $timestamps = false;

    protected $fillable = [
        'from_ad',
        'referer',
        'clicks'
    ];

    protected $guarded = [];
}
