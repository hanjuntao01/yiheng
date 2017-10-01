<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsServer
 */
class MerchantsServer extends Model
{
    protected $table = 'merchants_server';

    protected $primaryKey = 'server_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'suppliers_desc',
        'suppliers_percent',
        'commission_model',
        'bill_freeze_day',
        'cycle',
        'day_number',
        'bill_time'
    ];

    protected $guarded = [];
}
