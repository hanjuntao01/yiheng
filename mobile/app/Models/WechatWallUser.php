<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatWallUser
 */
class WechatWallUser extends Model
{
    protected $table = 'wechat_wall_user';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'wall_id',
        'nickname',
        'sex',
        'headimg',
        'status',
        'addtime',
        'checktime',
        'openid',
        'wechatname',
        'headimgurl'
    ];

    protected $guarded = [];
}
