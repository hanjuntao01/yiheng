<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatUser
 */
class WechatUser extends Model
{
    protected $table = 'wechat_user';

    protected $primaryKey = 'uid';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'subscribe',
        'openid',
        'nickname',
        'sex',
        'city',
        'country',
        'province',
        'language',
        'headimgurl',
        'subscribe_time',
        'remark',
        'privilege',
        'unionid',
        'groupid',
        'ect_uid',
        'bein_kefu',
        'parent_id',
        'from',
    ];

    protected $guarded = [];
}
