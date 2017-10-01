<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatPoint
 */
class WechatPoint extends Model
{
    protected $table = 'wechat_point';

    public $timestamps = false;

    protected $fillable = [
        'log_id',
        'wechat_id',
        'openid',
        'keywords',
        'createtime'
    ];

    protected $guarded = [];
}
