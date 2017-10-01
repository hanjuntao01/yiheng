<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeamLog
 */
class TeamLog extends Model
{
    protected $table = 'team_log';

    protected $primaryKey = 'team_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'start_time',
        'status',
        'is_show'
    ];

    protected $guarded = [];
}
