<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatRedpackAdvertice
 */
class WechatRedpackAdvertice extends Model
{
    protected $table = 'wechat_redpack_advertice';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'market_id',
        'icon',
        'content',
        'url'
    ];

    protected $guarded = [];
}
