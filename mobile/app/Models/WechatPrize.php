<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatPrize
 */
class WechatPrize extends Model
{
    protected $table = 'wechat_prize';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'openid',
        'prize_name',
        'issue_status',
        'winner',
        'dateline',
        'prize_type',
        'activity_type',
        'market_id'
    ];

    protected $guarded = [];
}
