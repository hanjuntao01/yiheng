<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsConshipping
 */
class GoodsConshipping extends Model
{
    protected $table = 'goods_conshipping';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'sfull',
        'sreduce'
    ];

    protected $guarded = [];
}
