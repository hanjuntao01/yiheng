<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeGoods
 */
class ExchangeGoods extends Model
{
    protected $table = 'exchange_goods';

    protected $primaryKey = 'eid';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'review_status',
        'review_content',
        'user_id',
        'exchange_integral',
        'is_exchange',
        'is_hot',
        'is_best'
    ];

    protected $guarded = [];
}
