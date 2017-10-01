<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatMenu
 */
class WechatMenu extends Model
{
    protected $table = 'wechat_menu';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'pid',
        'name',
        'type',
        'key',
        'url',
        'sort',
        'status'
    ];

    protected $guarded = [];
}
