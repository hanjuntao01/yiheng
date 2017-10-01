<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerBillGoods
 */
class SellerBillGoods extends Model
{
    protected $table = 'seller_bill_goods';

    public $timestamps = false;

    protected $fillable = [
        'rec_id',
        'order_id',
        'goods_id',
        'cat_id',
        'proportion',
        'goods_price',
        'goods_number',
        'goods_attr',
        'drp_money'
    ];

    protected $guarded = [];
}
