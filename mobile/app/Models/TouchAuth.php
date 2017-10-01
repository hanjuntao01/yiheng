<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchAuth
 */
class TouchAuth extends Model
{
    protected $table = 'touch_auth';

    public $timestamps = false;

    protected $fillable = [
        'auth_config',
        'type',
        'sort',
        'status'
    ];

    protected $guarded = [];
}
