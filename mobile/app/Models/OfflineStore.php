<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OfflineStore
 */
class OfflineStore extends Model
{
    protected $table = 'offline_store';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'stores_user',
        'stores_pwd',
        'stores_name',
        'country',
        'province',
        'city',
        'district',
        'stores_address',
        'stores_tel',
        'stores_opening_hours',
        'stores_traffic_line',
        'stores_img',
        'is_confirm',
        'add_time',
        'ec_salt'
    ];

    protected $guarded = [];
}
