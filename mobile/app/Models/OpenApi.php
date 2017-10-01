<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OpenApi
 */
class OpenApi extends Model
{
    protected $table = 'open_api';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'app_key',
        'action_code',
        'is_open',
        'add_time'
    ];

    protected $guarded = [];
}
