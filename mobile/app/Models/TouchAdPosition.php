<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchAdPosition
 */
class TouchAdPosition extends Model
{
    protected $table = 'touch_ad_position';

    protected $primaryKey = 'position_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'position_name',
        'ad_width',
        'ad_height',
        'position_desc',
        'position_style',
        'is_public',
        'theme',
        'tc_id',
        'tc_type'
    ];

    protected $guarded = [];
}
