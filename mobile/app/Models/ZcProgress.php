<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcProgress
 */
class ZcProgress extends Model
{
    protected $table = 'zc_progress';

    public $timestamps = false;

    protected $fillable = [
        'pid',
        'progress',
        'add_time',
        'img'
    ];

    protected $guarded = [];
}
