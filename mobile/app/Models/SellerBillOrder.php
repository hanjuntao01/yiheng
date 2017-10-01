<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerBillOrder
 */
class SellerBillOrder extends Model
{
    protected $table = 'seller_bill_order';

    public $timestamps = false;

    protected $fillable = [
        'bill_id',
        'user_id',
        'seller_id',
        'order_id',
        'order_sn',
        'order_status',
        'shipping_status',
        'pay_status',
        'order_amount',
        'return_amount',
        'return_shippingfee',
        'goods_amount',
        'tax',
        'shipping_fee',
        'insure_fee',
        'pay_fee',
        'pack_fee',
        'card_fee',
        'bonus',
        'integral_money',
        'coupons',
        'discount',
        'value_card',
        'money_paid',
        'surplus',
        'drp_money',
        'confirm_take_time',
        'chargeoff_status'
    ];

    protected $guarded = [];
}
