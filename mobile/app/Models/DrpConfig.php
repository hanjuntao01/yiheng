<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DrpConfig
 */
class DrpConfig extends Model
{
    protected $table = 'drp_config';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'type',
        'store_range',
        'value',
        'name',
        'warning',
        'sort_order'
    ];

    protected $guarded = [];
}
