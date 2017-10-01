<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcInitiator
 */
class ZcInitiator extends Model
{
    protected $table = 'zc_initiator';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'company',
        'img',
        'intro',
        'describe',
        'rank'
    ];

    protected $guarded = [];
}
