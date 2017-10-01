<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatShareCount
 */
class WechatShareCount extends Model
{
    protected $table = 'wechat_share_count';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'openid',
        'share_type',
        'link',
        'share_time'
    ];

    protected $guarded = [];
}
