<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsConsumption
 */
class GoodsConsumption extends Model
{
    protected $table = 'goods_consumption';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'cfull',
        'creduce'
    ];

    protected $guarded = [];
}
