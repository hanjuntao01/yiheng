<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatMessageLog
 */
class WechatMessageLog extends Model
{
    protected $table = 'wechat_message_log';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'fromusername',
        'createtime',
        'keywords',
        'msgtype',
        'msgid',
        'is_send'
    ];

    protected $guarded = [];
}
