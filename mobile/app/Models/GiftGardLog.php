<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GiftGardLog
 */
class GiftGardLog extends Model
{
    protected $table = 'gift_gard_log';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'gift_gard_id',
        'delivery_status',
        'addtime',
        'handle_type'
    ];

    protected $guarded = [];
}
