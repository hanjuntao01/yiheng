<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcFocus
 */
class ZcFocus extends Model
{
    protected $table = 'zc_focus';

    protected $primaryKey = 'rec_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pid',
        'add_time'
    ];

    protected $guarded = [];
}
