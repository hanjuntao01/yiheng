<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatQrcode
 */
class WechatQrcode extends Model
{
    protected $table = 'wechat_qrcode';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'type',
        'expire_seconds',
        'scene_id',
        'username',
        'function',
        'ticket',
        'qrcode_url',
        'endtime',
        'scan_num',
        'status',
        'sort'
    ];

    protected $guarded = [];
}
