<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsAccountLog
 */
class MerchantsAccountLog extends Model
{
    protected $table = 'merchants_account_log';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_money',
        'frozen_money',
        'change_time',
        'change_desc',
        'change_type'
    ];

    protected $guarded = [];
}
