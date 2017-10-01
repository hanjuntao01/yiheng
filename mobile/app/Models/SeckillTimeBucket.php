<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SeckillTimeBucket
 */
class SeckillTimeBucket extends Model
{
    protected $table = 'seckill_time_bucket';

    public $timestamps = false;

    protected $fillable = [
        'begin_time',
        'end_time',
        'title'
    ];

    protected $guarded = [];
}
