<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerCommissionBill
 */
class SellerCommissionBill extends Model
{
    protected $table = 'seller_commission_bill';

    public $timestamps = false;

    protected $fillable = [
        'seller_id',
        'bill_sn',
        'order_amount',
        'shipping_amount',
        'return_amount',
        'return_shippingfee',
        'proportion',
        'commission_model',
        'gain_commission',
        'should_amount',
        'chargeoff_time',
        'settleaccounts_time',
        'start_time',
        'end_time',
        'chargeoff_status',
        'bill_cycle',
        'bill_apply',
        'apply_note',
        'apply_time',
        'operator',
        'check_status',
        'reject_note',
        'check_time',
        'frozen_money',
        'frozen_data',
        'frozen_time'
    ];

    protected $guarded = [];
}
