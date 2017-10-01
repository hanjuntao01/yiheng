<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatCustomMessage
 */
class WechatCustomMessage extends Model
{
    protected $table = 'wechat_custom_message';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'uid',
        'msg',
        'send_time',
        'is_wechat_admin'
    ];

    protected $guarded = [];
}
