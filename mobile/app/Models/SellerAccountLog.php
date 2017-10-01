<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerAccountLog
 */
class SellerAccountLog extends Model
{
    protected $table = 'seller_account_log';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'real_id',
        'ru_id',
        'order_id',
        'amount',
        'frozen_money',
        'certificate_img',
        'deposit_mode',
        'log_type',
        'apply_sn',
        'pay_id',
        'pay_time',
        'admin_note',
        'add_time',
        'seller_note',
        'is_paid'
    ];

    protected $guarded = [];
}
