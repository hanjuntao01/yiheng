<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImDialog
 */
class ImDialog extends Model
{
    protected $table = 'im_dialog';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'services_id',
        'goods_id',
        'store_id',
        'start_time',
        'end_time',
        'origin',
        'status'
    ];

    protected $guarded = [];
}
