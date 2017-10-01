<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DrpType
 */
class DrpType extends Model
{
    protected $table = 'drp_type';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cat_id',
        'goods_id',
        'type',
        'add_time'
    ];

    protected $guarded = [];
}
