<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatUserTaglist
 */
class WechatUserTaglist extends Model
{
    protected $table = 'wechat_user_taglist';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'tag_id',
        'name',
        'count',
        'sort'
    ];

    protected $guarded = [];
}
