<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatRedpackLog
 */
class WechatRedpackLog extends Model
{
    protected $table = 'wechat_redpack_log';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'market_id',
        'hb_type',
        'openid',
        'hassub',
        'money',
        'time',
        'mch_billno',
        'mch_id',
        'wxappid',
        'bill_type'
    ];

    protected $guarded = [];
}
