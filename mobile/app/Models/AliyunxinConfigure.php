<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AliyunxinConfigure
 */
class AliyunxinConfigure extends Model
{
    protected $table = 'aliyunxin_configure';

    public $timestamps = false;

    protected $fillable = [
        'temp_id',
        'temp_content',
        'add_time',
        'set_sign',
        'send_time',
        'signature'
    ];

    protected $guarded = [];
}
