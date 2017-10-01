<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DrpLog
 */
class DrpLog extends Model
{
    protected $table = 'drp_log';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'time',
        'user_id',
        'user_name',
        'money',
        'point',
        'drp_level',
        'is_separate',
        'separate_type'
    ];

    protected $guarded = [];
}
