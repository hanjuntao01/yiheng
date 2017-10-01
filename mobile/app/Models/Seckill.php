<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Seckill
 */
class Seckill extends Model
{
    protected $table = 'seckill';

    protected $primaryKey = 'sec_id';

    public $timestamps = false;

    protected $fillable = [
        'acti_title',
        'begin_time',
        'is_putaway',
        'acti_time',
        'add_time'
    ];

    protected $guarded = [];
}
