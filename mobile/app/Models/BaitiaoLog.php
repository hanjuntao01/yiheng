<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaitiaoLog
 */
class BaitiaoLog extends Model
{
    protected $table = 'baitiao_log';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'baitiao_id',
        'user_id',
        'use_date',
        'repay_date',
        'order_id',
        'repayed_date',
        'is_repay',
        'is_stages',
        'stages_total',
        'stages_one_price',
        'yes_num',
        'is_refund'
    ];

    protected $guarded = [];
}
