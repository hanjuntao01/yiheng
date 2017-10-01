<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatWallMsg
 */
class WechatWallMsg extends Model
{
    protected $table = 'wechat_wall_msg';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'wall_id',
        'user_id',
        'content',
        'addtime',
        'checktime',
        'status'
    ];

    protected $guarded = [];
}
