<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatReply
 */
class WechatReply extends Model
{
    protected $table = 'wechat_reply';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'type',
        'content',
        'media_id',
        'rule_name',
        'add_time',
        'reply_type'
    ];

    protected $guarded = [];
}
