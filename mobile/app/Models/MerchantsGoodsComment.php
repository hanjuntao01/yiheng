<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsGoodsComment
 */
class MerchantsGoodsComment extends Model
{
    protected $table = 'merchants_goods_comment';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'comment_start',
        'comment_end',
        'comment_last_percent'
    ];

    protected $guarded = [];
}
