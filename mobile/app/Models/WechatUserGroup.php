<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatUserGroup
 */
class WechatUserGroup extends Model
{
    protected $table = 'wechat_user_group';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'group_id',
        'name',
        'count',
        'sort'
    ];

    protected $guarded = [];
}
