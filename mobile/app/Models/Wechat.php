<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wechat
 */
class Wechat extends Model
{
    protected $table = 'wechat';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'orgid',
        'weixin',
        'token',
        'appid',
        'appsecret',
        'encodingaeskey',
        'type',
        'oauth_status',
        'secret_key',
        'oauth_redirecturi',
        'oauth_count',
        'time',
        'sort',
        'status',
        'default_wx',
        'ru_id'
    ];

    protected $guarded = [];
}
