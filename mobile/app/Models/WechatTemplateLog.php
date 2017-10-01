<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatTemplateLog
 */
class WechatTemplateLog extends Model
{
    protected $table = 'wechat_template_log';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'msgid',
        'code',
        'openid',
        'data',
        'url',
        'status'
    ];

    protected $guarded = [];
}
