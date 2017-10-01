<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatUserTag
 */
class WechatUserTag extends Model
{
    protected $table = 'wechat_user_tag';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'tag_id',
        'openid'
    ];

    protected $guarded = [];
}
