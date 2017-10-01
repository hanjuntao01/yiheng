<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsTransport
 */
class GoodsTransport extends Model
{
    protected $table = 'goods_transport';

    protected $primaryKey = 'tid';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'type',
        'freight_type',
        'title',
        'update_time'
    ];

    protected $guarded = [];
}
