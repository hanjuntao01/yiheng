<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchNav
 */
class TouchNav extends Model
{
    protected $table = 'touch_nav';

    public $timestamps = false;

    protected $fillable = [
        'ctype',
        'cid',
        'name',
        'ifshow',
        'vieworder',
        'opennew',
        'url',
        'type',
        'pic'
    ];

    protected $guarded = [];
}
