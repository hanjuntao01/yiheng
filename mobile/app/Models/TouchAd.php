<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchAd
 */
class TouchAd extends Model
{
    protected $table = 'touch_ad';

    protected $primaryKey = 'ad_id';

    public $timestamps = false;

    protected $fillable = [
        'position_id',
        'media_type',
        'ad_name',
        'ad_link',
        'link_color',
        'ad_code',
        'start_time',
        'end_time',
        'link_man',
        'link_email',
        'link_phone',
        'click_count',
        'enabled',
        'is_new',
        'is_hot',
        'is_best',
        'public_ruid',
        'ad_type',
        'goods_name'
    ];

    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo('App\Models\TouchAdPosition', 'position_id', 'position_id');
    }
}
