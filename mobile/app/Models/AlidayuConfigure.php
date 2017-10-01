<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AlidayuConfigure
 */
class AlidayuConfigure extends Model
{
    protected $table = 'alidayu_configure';

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
