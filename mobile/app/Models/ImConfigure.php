<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImConfigure
 */
class ImConfigure extends Model
{
    protected $table = 'im_configure';

    public $timestamps = false;

    protected $fillable = [
        'ser_id',
        'type',
        'content',
        'is_on'
    ];

    protected $guarded = [];
}
