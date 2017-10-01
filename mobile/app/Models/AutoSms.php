<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AutoSms
 */
class AutoSms extends Model
{
    protected $table = 'auto_sms';

    protected $primaryKey = 'item_id';

    public $timestamps = false;

    protected $fillable = [
        'item_type',
        'user_id',
        'ru_id',
        'order_id',
        'add_time'
    ];

    protected $guarded = [];
}
