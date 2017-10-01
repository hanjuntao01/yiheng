<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SingleSunImages
 */
class SingleSunImages extends Model
{
    protected $table = 'single_sun_images';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'order_id',
        'goods_id',
        'img_file',
        'img_thumb',
        'cont_desc',
        'comment_id',
        'img_type'
    ];

    protected $guarded = [];
}
