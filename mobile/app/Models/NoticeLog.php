<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NoticeLog
 */
class NoticeLog extends Model
{
    protected $table = 'notice_log';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'email',
        'send_ok',
        'send_type',
        'send_time'
    ];

    protected $guarded = [];
}
