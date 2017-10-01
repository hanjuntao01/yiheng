<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatExtend
 */
class WechatExtend extends Model
{
    protected $table = 'wechat_extend';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'name',
        'keywords',
        'command',
        'config',
        'type',
        'enable',
        'author',
        'website'
    ];

    protected $guarded = [];
}
