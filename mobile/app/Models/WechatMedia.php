<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatMedia
 */
class WechatMedia extends Model
{
    protected $table = 'wechat_media';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'title',
        'command',
        'author',
        'is_show',
        'digest',
        'content',
        'link',
        'file',
        'size',
        'file_name',
        'thumb',
        'add_time',
        'edit_time',
        'type',
        'article_id',
        'sort'
    ];

    protected $guarded = [];
}
